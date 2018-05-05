<?php

namespace AppBundle\Service;

use AppBundle\Entity\ClientUser;
use AppBundle\Entity\Warning;
use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;
use sngrl\PhpFirebaseCloudMessaging\Notification;

class WarningService
{
    static $notificationTitle = "%s Warning!";
    static $notificationBody = "A new %s warning has been issued at coordinates [%f, %f]";

    private $serverKey;

    public function __construct($serverKey)
    {
        $this->serverKey = $serverKey;
    }

    public function broadcastWarning(Warning $warning, array $users, string $priority = 'high'): void
    {
        if (empty($users)) {
            return;
        }

        $client = $this->initializeClient();

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

    private function initializeClient(): Client
    {
        $client = new Client();
        $client->setApiKey($this->serverKey);
        $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());

        return $client;
    }
}