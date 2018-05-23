<?php

namespace AppBundle\Event;

use AppBundle\Entity\Warning;
use Symfony\Component\EventDispatcher\Event;

class WarningConfirmedEvent extends Event
{
    const NAME = 'warning.confirmed';

    private $confirmedWarning;

    private $confirmedWarningSiblings;


    public function getConfirmedWarning(): Warning
    {
        return $this->confirmedWarning;
    }

    public function setConfirmedWarning(Warning $confirmedWarning): void
    {
        $this->confirmedWarning = $confirmedWarning;
    }

    public function getConfirmedWarningSiblings(): array
    {
        return $this->confirmedWarningSiblings;
    }

    public function setConfirmedWarningSiblings(array $confirmedWarningSiblings): void
    {
        $this->confirmedWarningSiblings = $confirmedWarningSiblings;
    }

    public function addSiblingWarning(Warning $warning): void
    {
        $this->confirmedWarningSiblings[] = $warning;
    }

}