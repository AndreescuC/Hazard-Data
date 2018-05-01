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
     * @param float $apiKeyGenerationMinValue
     * @param float $apiKeyGenerationMaxValue
     * @param float $apiKetGenerationAttempts
     */
    public function __construct(
        ManagerRegistry $doctrine,
        float $apiKeyGenerationMinValue,
        float $apiKeyGenerationMaxValue,
        float $apiKetGenerationAttempts
    ) {
        $this->doctrine = $doctrine;
        $this->apiKeyGenerationMaxValue = $apiKeyGenerationMaxValue;
        $this->apiKetGenerationAttempts = $apiKetGenerationAttempts;
        $this->apiKeyGenerationMinValue = $apiKeyGenerationMinValue;
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
     * @param array $credentials
     * @return ClientUser|null
     */
    public function getUserByCredentials(array $credentials)
    {
        /** @var ClientUserRepository $clientUserRepo */
        $clientUserRepo = $this->doctrine->getRepository(ClientUser::class);
        /** @var ClientUser $user */
        $user = $clientUserRepo->findOneBy(['username' => $credentials['username']]);
        if (!$user) {
            return NULL;
        }
        return password_verify($credentials['password'], $user->getPassword()) ? $user : NULL;
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
        $max_value = @$this->apiKeyGenerationMaxValue ?: 999999;
        $max_attempts = @$this->apiKetGenerationAttempts ?: $min_value - $max_value;
        $attempt = 0;

        $repo = $this->getManager()->getRepository(ClientUser::class);

        while (true) {
            $apiKey = mt_rand($min_value, $max_value);
            $item = $repo->findOneBy(['userAPIKey' => $apiKey]);

            if (!$item) {
                return $apiKey;
            }

            if ($attempt++ > $max_attempts) {
                throw new \ApiKeyGenerationException();
            }
        }

        return -1;
    }

    /**
     * @param array $data
     * @return int|null
     */
    public function saveNewUser(array $data): ?int
    {
        $user = new ClientUser($data['username'], $data['password']);
        if (isset($data['first_name'])) {
            $user->setFirstName($data['first_name']);
        }
        if (isset($data['last_name'])) {
            $user->setLastName($data['last_name']);
        }
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (isset($data['home_town'])) {
            $user->setHomeTown($data['home_town']);
        }

        $em = $this->getManager();
        try {
            $apiKey = $this->generateNewApiKey();
            $user->setUserAPIKey($apiKey);
            $em->persist($user);
            $em->flush();

        } catch (\Exception $ex) {

            //TODO: log this somehow
            $em->detach($user);
            return NULL;
        }
        return $apiKey;
    }

    /**
     * @param $username
     * @return bool
     */
    public function validateRegisterRequest(string $username): bool
    {
        /** @var ClientUserRepository $clientUserRepo */
        $clientUserRepo = $this->getManager()->getRepository(ClientUser::class);
        $user = $clientUserRepo->findOneBy(['username' => $username]);
        return $user ? false: true;
    }


    /**
     * @param string $connection
     * @return ObjectManager
     */
    public function getManager(string $connection = NULL): ObjectManager
    {
        return $this->doctrine->getManager($connection);
    }

}