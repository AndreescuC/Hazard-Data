<?php

namespace AppBundle\Service;

use AppBundle\Entity\ClientUser;
use AppBundle\Repository\ClientUserRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ClientUserService
{
    /** @var ManagerRegistry $entityManager*/
    private $doctrine;

    /**
     * ClientUserService constructor.
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param string $username
     * @return null|object
     */
    public function getUserByUsername(string $username)
    {
        /** @var ClientUserRepository $clientUserRepo */
        $clientUserRepo = $this->doctrine->getRepository(ClientUser::class);
        return $clientUserRepo->findOneBy(['username' => $username]);
    }

    /**
     * @param string $apikey
     * @return object|null
     */
    public function getUserByApiKey(string $apikey)
    {
        /** @var ClientUserRepository $clientUserRepo */
        $clientUserRepo = $this->doctrine->getRepository(ClientUser::class);
        return $clientUserRepo->findOneBy(['userAPIKey' => $apikey]);
    }

}