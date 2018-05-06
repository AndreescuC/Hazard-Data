<?php

namespace AppBundle\Service;

use AppBundle\Entity\AppConfig;
use AppBundle\Repository\AppConfigRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class AppConfigService
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getConfigByName(string $name): mixed
    {
        return $this->getConfigRepository()->findOneBy(['name' => $name]);
    }

    private function getConfigRepository(): AppConfigRepository
    {
        return $this->doctrine->getRepository(AppConfig::class);
    }
}