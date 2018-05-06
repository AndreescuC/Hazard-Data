<?php

namespace AppBundle\Command;

use AppBundle\Entity\DataProvider;
use AppBundle\Provider\DataHazardProvider;
use AppBundle\Repository\DataProviderRepository;
use AppBundle\Service\DataProviderService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class QueryDataProvidersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('provider:query-all');
        $this->setDescription('Queries all active registered data providers and broadcasts the relevant events');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var DataProviderRepository $dataProviderRepo */
        $dataProviderRepo = $this->getManager()->getRepository(DataProvider::class);
        $providers = $dataProviderRepo->getActiveProviders();

        /** @var DataProviderService $dataProviderService */
        $dataProviderService = $this->getContainer()->get('app.service.data_provider_service');

        $events = [];
        /** @var DataProvider $providerEntity */
        foreach ($providers as $providerEntity) {
            $provider = $dataProviderService->getProviderByCode($providerEntity->getCode());
            if (!$provider) {
                continue;
            }
            $provider->setProviderEntity($providerEntity);
            if ($provider->setRequestParameters(DataHazardProvider::DEFAULT_PARAMETERS) && $provider->makeRequest()) {
                array_merge($events, $provider->getFormattedEvents());
            }
        }
    }

    /**
     * @return ObjectManager
     */
    private function getManager(): ObjectManager
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }
}