<?php

namespace AppBundle\Repository;

use AppBundle\Entity\ClientUser;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class ClientUserRepository extends EntityRepository
{

    public function countAllUsers(): int
    {
        $qb = $this->getQueryBuilder()
            ->select('count(cu.id)');
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countAllActiveUsers(): int
    {
        $qb = $this->getQueryBuilder()
            ->select('count(cu.id)')
            ->where('cu.status = :active')
            ->setParameter('active', ClientUser::STATUS_ACTIVE);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countUsersByTimeFrame(\DateTime $after, \DateTime $before = NULL): int
    {
        $qb = $this->getQueryBuilder()
            ->select('count(cu.id)')
            ->where('cu.registerDate > :after')
            ->andWhere('cu.registerDate < :before')
            ->setParameter('after', $after)
            ->setParameter('before', ($before ?: new \DateTime()));
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return QueryBuilder
     */
    private function getQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('cu');
    }
}