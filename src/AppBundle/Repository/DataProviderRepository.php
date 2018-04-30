<?php

namespace AppBundle\Repository;

use AppBundle\Entity\DataProvider;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class DataProviderRepository extends EntityRepository
{

    /**
     * @return array
     */
    public function getActiveProviders()
    {
        $qb = $this->getQueryBuilder();
        $qb->select()
            ->where('dp.status = :active')
            ->setParameter('active', DataProvider::STATUS_ACTIVE);
        return $qb->getQuery()->getResult();
    }

    /**
     * @return QueryBuilder
     */
    private function getQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('dp');
    }
}