<?php

namespace AppBundle\Event;

use AppBundle\Entity\Warning;
use AppBundle\Repository\WarningRepository;
use AppBundle\Service\WarningService;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WarningSubscriber implements EventSubscriberInterface
{
    private $doctrine;

    private $warningService;

    public function __construct(ManagerRegistry $doctrine, WarningService $warningService)
    {
        $this->doctrine = $doctrine;
        $this->warningService = $warningService;
    }

    public static function getSubscribedEvents()
    {
        return array(
            WarningConfirmedEvent::NAME => 'onWarningConfirmation',
        );
    }

    public function onWarningConfirmation(WarningConfirmedEvent $event): void
    {
        $warnings = $event->getConfirmedWarningSiblings();
        $triggerWarning = $event->getConfirmedWarning();
        /** @var WarningRepository $repo */
        $repo = $this->doctrine->getRepository(Warning::class);
        $repo->updateStatus($warnings, Warning::STATUS_CONFIRMED);
        $repo->updateStatus([$triggerWarning], Warning::STATUS_CONFIRMED_TRIGGER);

        $this->warningService->broadcastWarning($triggerWarning);
    }

}