<?php

namespace AppBundle\Service;

use AppBundle\Entity\ClientUser;
use AppBundle\Entity\Warning;
use Doctrine\Common\Persistence\ManagerRegistry;

class StatisticsService
{
    private $doctrine;

    private $clientRepo;

    private $warningRepo;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->clientRepo = $this->doctrine->getRepository(ClientUser::class);
        $this->warningRepo = $this->doctrine->getRepository(Warning::class);
    }

    public function getDashboardStatistics(): array
    {
        $statistics = [];
        $statistics['cumulative'] = $this->getCumulativeStatistics();
        $statistics['weekly'] = $this->getWeeklyStatistics();

        return $statistics;
    }

    private function getCumulativeStatistics(): array
    {
        $statistics = [];
        $statistics['registered_users'] = $this->clientRepo->countAllUsers();
        $statistics['active_users'] = $this->clientRepo->countAllActiveUsers();
        $statistics['user_confirmed_warnings'] = $this->warningRepo->countUserConfirmedWarningsByTimeFrame();
        $statistics['api_confirmed_warnings'] = $this->warningRepo->countApiConfirmedWarningsByTimeFrame();

        return $statistics;
    }

    private function getWeeklyStatistics(): array
    {
        $aWeekAgo = new \DateTime();
        $aWeekAgo->sub(new \DateInterval('P7D'));

        $statistics['new_users'] = [
            'total' => $this->clientRepo->countUsersByTimeFrame($aWeekAgo),
            'byDay' => $this->getWeeklyStatisticsByDay([$this->clientRepo, 'countUsersByTimeFrame'])
        ];

        $statistics['user_warnings'] = [
            'total' => $this->warningRepo->countUserWarningsByTimeFrame($aWeekAgo),
            'byDay' => $this->getWeeklyStatisticsByDay([$this->warningRepo, 'countUserWarningsByTimeFrame'])
        ];

        $statistics['confirmed_user_warnings'] = [
            'total' => $this->warningRepo->countUserConfirmedWarningsByTimeFrame($aWeekAgo),
            'byDay' => $this->getWeeklyStatisticsByDay([$this->warningRepo, 'countUserConfirmedWarningsByTimeFrame'])
        ];

        $statistics['warnings_honesty'] = round(
            (float)$statistics['confirmed_user_warnings']['total']
            /(float)$statistics['user_warnings']['total'], 2
        );

        return $statistics;
    }

    private function getWeeklyStatisticsByDay(array $callback): array
    {
        $daysOfTheWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        $startOfDay = new \DateTime();
        $endOfDay = new \DateTime();
        $startOfDay->sub(new \DateInterval('P7D'));
        $endOfDay->sub(new \DateInterval('P6D'));

        $statistics = [];
        $oneDayInterval = new \DateInterval('P1D');
        for ($i=0; $i<7; $i++) {
            $statistics[$daysOfTheWeek[$i]] = call_user_func_array($callback, [$startOfDay, $endOfDay]);
            $startOfDay->add($oneDayInterval);
            $endOfDay->add($oneDayInterval);
        }

        return $statistics;
    }

}