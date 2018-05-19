<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Hazard;
use AppBundle\Entity\Warning;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class WarningRepository extends EntityRepository
{

    public function updateStatus(array $warnings, int $status)
    {
        $warningIds = [];
        /** @var Warning $warning */
        foreach ($warnings as $warning) {
            $warningIds[] = $warning->getId();
        }

        $qb = $this->getQueryBuilder()
            ->set('w.status', $status)
            ->where('w.id IN (:warnings)')
            ->setParameter('warnings', $warningIds);

        return $qb->getQuery()->getResult();
    }

    public function getEntriesByStatusAndHazard(Hazard $hazard, int $status): ?array
    {
        $qb = $this->getQueryBuilder()
            ->where('w.hazard = :hazard')
            ->andWhere('w.status = : :status')
            ->setParameter('hazard', $hazard)
            ->setParameter('status', $status);

        return $qb->getQuery()->getResult();
    }

    public function countApiConfirmedWarningsByTimeFrame(\DateTime $after = null): int
    {
        $qb = $this->getQueryBuilder();
        $qb ->select('count(w.id)')
            ->where('w.status IN (:confirmed)')
            ->andWhere('w.extId LIKE :userOriginated')
            ->setParameter('confirmed', [Warning::STATUS_CONFIRMED_TRIGGER, Warning::STATUS_CONFIRMED])
            ->setParameter('userOriginated', 'user%');

        if ($after instanceof \DateTime) {
            $qb ->andWhere('w.dateCreated >= :after')
                ->setParameter('after', $after);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countUserConfirmedWarningsByTimeFrame(\DateTime $after = null, \DateTime $before = NULL): int
    {
        $qb = $this->getQueryBuilder()
            ->select('count(w.id)')
            ->where('w.status IN (:confirmed)')
            ->andWhere('w.extId LIKE :userOriginated')
            ->andWhere('cu.registerDate < :before')
            ->setParameter('confirmed', [Warning::STATUS_CONFIRMED_TRIGGER, Warning::STATUS_CONFIRMED])
            ->setParameter('before', !is_null($before) ?: new \DateTime())
            ->setParameter('userOriginated', 'user%');

        if ($after instanceof \DateTime) {
            $qb ->andWhere('w.dateCreated >= :after')
                ->setParameter('after', $after);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countUserWarningsByTimeFrame(\DateTime $after = null, \DateTime $before = NULL): int
    {
        $qb = $this->getQueryBuilder()
            ->select('count(w.id)')
            ->andWhere('w.extId LIKE :userOriginated')
            ->andWhere('cu.registerDate < :before')
            ->setParameter('before', !is_null($before) ?: new \DateTime())
            ->setParameter('userOriginated', 'user%');

        if ($after instanceof \DateTime) {
            $qb ->andWhere('w.dateCreated >= :after')
                ->setParameter('after', $after);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return QueryBuilder
     */
    private function getQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('w');
    }
}