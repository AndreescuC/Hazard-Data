<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Hazard;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class WarningRepository extends EntityRepository
{

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