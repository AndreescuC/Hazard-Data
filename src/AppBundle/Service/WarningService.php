<?php

namespace AppBundle\Service;

use AppBundle\Entity\ClientUser;
use AppBundle\Entity\Hazard;
use AppBundle\Entity\Warning;
use AppBundle\Repository\ClientUserRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;
use sngrl\PhpFirebaseCloudMessaging\Notification;

class WarningService
{
    static $notificationTitle = "%s Warning!";
    static $notificationBody = "A new %s warning has been issued at coordinates [%f, %f]";

    private $serverKey;

    private $doctrine;

    private $appConfigService;

    public function __construct(string $serverKey, ManagerRegistry $doctrine, AppConfigService $appConfigService)
    {
        $this->serverKey = $serverKey;
        $this->doctrine = $doctrine;
        $this->appConfigService = $appConfigService;
    }

    public function handleIncomingWarning(array $data): bool
    {
        /** @var ClientUserRepository $repo */
        $userRepo = $this->doctrine->getRepository(ClientUser::class);
        $user = $userRepo->findOneBy(['userAPIKey' => $data['sender_apikey']]);
        if (!$user instanceof ClientUser) {
            return false;
        }
        $data['ext_id'] = 'user' . $user->getId();
        $data['trust_level'] = $user->getTrustLevel();

        try {
            $this->save($data);
        } catch (\Exception $e) {
            //TODO: log this somehow...or do smth
            return false;
        }

        return true;
    }

    public function broadcastWarning(Warning $warning, array $users, string $priority = 'high'): void
    {
        if (empty($users)) {
            return;
        }

        $client = $this->initializeFirebaseClient();

        $message = $this->composeMessage($warning);
        $message->setPriority($priority);

        /** @var ClientUser $user */
        foreach ($users as $user) {
            $message->addRecipient(new Device($user->getFirebaseToken()));
        }

        $response = $client->send($message);
        var_dump($response->getStatusCode());
        var_dump($response->getBody()->getContents());
    }

    private function composeMessage(Warning $warning): Message
    {
        $message = new Message();

        $hazardName = $warning->getHazard()->getName();
        $title = sprintf(self::$notificationTitle, $hazardName);
        $body = sprintf(self::$notificationTitle, $hazardName,
            $warning->getLocationLat(), $warning->getLocationLong());

        $data = [
            'hazard' => $warning->getHazard()->getName(),
            'safety_measures' => $warning->getHazard()->getSafetyMeasures(),
            'lat' => $warning->getLocationLong(),
            'long' => $warning->getLocationLat()
        ];

        $message
            ->setNotification(new Notification($title, $body))
            ->setData($data);

        return $message;
    }

    private function initializeFirebaseClient(): Client
    {
        $client = new Client();
        $client->setApiKey($this->serverKey);
        $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());

        return $client;
    }

    private function save(array $data): void
    {
        $warning = new Warning();
        $warning->setExtId($data['ext_id']);
        $warning->setTrustLevel($data['trust_level']);
        $warning->setLocationLat($data['hazard']['loc']['lat']);
        $warning->setLocationLong($data['hazard']['loc']['long']);

        if (isset($data['hazard']['type'])) {
            $hazardRepo = $this->doctrine->getRepository(Hazard::class);
            $hazard = $hazardRepo->findOneBy(['id' => $data['hazard']['type']]);
            if ($hazard instanceof Hazard) {
                $warning->setHazard($hazard);
            }
        }

        $population = $data['hazard']['population'];
        if (isset($population)) {
            if (in_array($population, Warning::getPopulationValues())) {
                $warning->setPopulation($population);
            }
        }

        $em = $this->getManager();
        $em->persist($warning);
        $em->flush();
    }

    private function getManager()
    {
        return $this->doctrine->getManager();
    }
}