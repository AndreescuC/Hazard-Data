<?php

namespace AppBundle\Service;

use AppBundle\Entity\ClientUser;
use AppBundle\Entity\Warning;
use Doctrine\Common\Persistence\ManagerRegistry;

class StatisticsService
{

    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getDashboardStatistics(): array
    {
        $statistics = [];
        $clientRepo = $this->doctrine->getRepository(ClientUser::class);
        $statistics['registered_users'] = $clientRepo->countAllUsers();
        $statistics['active_users'] = $clientRepo->countAllActiveUsers();

        $warningsRepo = $this->doctrine->getRepository(Warning::class);
        $statistics['user_confirmed_warnings'] = $warningsRepo->countUserConfirmedWarnings();
        $statistics['api_confirmed_warnings'] = $warningsRepo->countUserConfirmedWarnings();

        $finalStatistics['cumulative'] = $statistics;

        $statistics = [];
        $aWeekAgo = new \DateTime();
        $aWeekAgo->sub(new \DateInterval('P7D'));

        $statistics['new_users'] = $clientRepo->countUsersByTimeFrame($aWeekAgo);
        $statistics['logins'] = 10;
        $statistics['user_warnings'] = 10;
        $statistics['confirmed_user_warnings'] = 10;
        $statistics['warnings_honesty'] = 10;

        $finalStatistics['weekly'] = $statistics;

        return $finalStatistics;
    }


}