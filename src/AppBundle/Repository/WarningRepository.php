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

    /**
     * @return QueryBuilder
     */
    private function getQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('w');
    }
}