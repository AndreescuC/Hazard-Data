<?php

namespace AppBundle\Service;

use AppBundle\Entity\ClientUser;
use AppBundle\Repository\ClientUserRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;

class ClientUserService
{

    static $RANDOM_GENERATOR_FAIL_CODE = 1001;

    /** @var ManagerRegistry $doctrine*/
    private $doctrine;

    /** @var  float  $apiKeyGenerationMinValue*/
    private $apiKeyGenerationMinValue;

    /** @var  float $apiKeyGenerationMaxValue */
    private $apiKeyGenerationMaxValue;

    /** @var  float $apiKetGenerationAttempts */
    private $apiKetGenerationAttempts;

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

    /**
     * @param ClientUser $user
     * @return string
     * @throws \ApiKeyGenerationException
     */
    public function refreshApiKeyForUser(ClientUser $user): string
    {
        $newApiKey = $this->generateNewApiKey();
        $user->setUserAPIKey($newApiKey);
        $this->getManager()->flush();

        return $newApiKey;
    }

    /**
     * @return int
     * @throws \Exception
     */
    private function generateNewApiKey()
    {
        $min_value = @$this->apiKeyGenerationMinValue ?: 100000;
        $max_value = @$this->apiKeyGenerationMinValue ?: 999999;
        $max_attempts = @$this->apiKeyGenerationMinValue ?: $min_value - $max_value;
        $attempt = 0;

        $em = $this->getManager();

        while (true) {
            $id = mt_rand($min_value, $max_value);
            $item = $em->find(ClientUser::class, $id);

            if (!$item) {
                return $id;
            }

            if ($attempt++ > $max_attempts) {
                throw new \ApiKeyGenerationException();
            }
        }

        return -1;
    }

    /**
     * @param string $connection
     * @return ObjectManager
     */
    public function getManager(string $connection = ''): ObjectManager
    {
        return $this->doctrine->getManager($connection);
    }

}