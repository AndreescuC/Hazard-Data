<?php

namespace AppBundle\Service;

use AppBundle\Entity\AppConfig;
use AppBundle\Entity\ClientUser;
use AppBundle\Entity\Hazard;
use AppBundle\Entity\Warning;
use AppBundle\Event\WarningConfirmedEvent;
use AppBundle\Event\WarningSubscriber;
use AppBundle\Repository\ClientUserRepository;
use AppBundle\Repository\WarningRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;
use sngrl\PhpFirebaseCloudMessaging\Notification;
use Symfony\Component\EventDispatcher\EventDispatcher;

class WarningService
{
    const NOTIFICATION_TITLE = "%s Warning!";
    const NOTIFICATION_BODY = "A new %s warning has been issued at coordinates [%f, %f]";

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
        $data['ext_id'] = 'user' . $user->getId() . '-' . $data['ext_id'];
        $data['trust_level'] = $user->getTrustLevel();

        try {
            $this->save($data);
        } catch (\Exception $e) {
            //TODO: log this somehow...or do smth
            return false;
        }

        return true;
    }

    public function broadcastWarning(Warning $warning, array $users = [], string $priority = 'high'): void
    {
        if (empty($users)) {
            $users = $this->getEligibleUsers();
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
        $title = sprintf(self::NOTIFICATION_TITLE, $hazardName);
        $body = sprintf(self::NOTIFICATION_BODY, $hazardName,
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

    private function getEligibleUsers(): array
    {
        return $this->getManager()->getRepository(ClientUser::class)->findAll([
            'status' => ClientUser::STATUS_ACTIVE
            ]);
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

        $gravity = $data['hazard']['population'];
        if (isset($gravity)) {
            if (in_array($gravity, Warning::getGravityValues())) {
                $warning->setGravity($gravity);
            }
        }
        //TODO: replace with $this->resolveWarningStatus($warning)
        $warning->setStatus(Warning::STATUS_PENDING);

        $em = $this->getManager();
        $em->persist($warning);
        $em->flush();
    }

    private function resolveWarningStatus(Warning $warning): int
    {
        $confirmedWarnings = $this->getWarningInstancesByStatus($warning, Warning::STATUS_CONFIRMED);
        if (!empty($confirmedWarnings)) {
            return Warning::STATUS_CONFIRMED;
        }

        $pendingWarnings = $this->getWarningInstancesByStatus($warning, Warning::STATUS_PENDING);
        $trustThreshold = $this->appConfigService->getConfigByName(AppConfig::CONFIG_TRUST_THRESHOLD);
        $trustLevel = $warning->getTrustLevel();

        /** @var Warning $pendingWarning */
        foreach ($pendingWarnings as $pendingWarning) {
            $trustLevel += $pendingWarning->getTrustLevel();
            if ($trustLevel >= $trustThreshold) {
                $event = new WarningConfirmedEvent();
                $event->setConfirmedWarning($warning);
                $event->setConfirmedWarningSiblings($pendingWarnings);

                $dispatcher = new EventDispatcher();
                $dispatcher->addSubscriber(new WarningSubscriber($this->doctrine, $this));
                $dispatcher->dispatch($event);

                return Warning::STATUS_CONFIRMED;
            }
        }
        return Warning::STATUS_PENDING;
    }

    private function getWarningInstancesByStatus(Warning $warning, int $status): array
    {
        /** @var WarningRepository $repo */
        $repo = $this->getManager()->getRepository(Warning::class);
        $matchingWarnings = $repo->getEntriesByStatusAndHazard($warning->getHazard(), $status);

        $radius = $this->appConfigService->getConfigByName(AppConfig::CONFIG_RADIUS);

        $proximityWarnings = [];
        /** @var Warning $matchingWarning */
        foreach ($matchingWarnings as $matchingWarning) {
            $distance = $this->appConfigService->computeHaversineGreatCircleDistance(
                $warning->getLocationLat(),
                $warning->getLocationLong(),
                $matchingWarning->getLocationLat(),
                $matchingWarning->getLocationLong()
            );
            if ($distance <= $radius) {
                $proximityWarnings[] = $matchingWarning;
            }
        }

        return $proximityWarnings;
    }

    private function getManager()
    {
        return $this->doctrine->getManager();
    }
}