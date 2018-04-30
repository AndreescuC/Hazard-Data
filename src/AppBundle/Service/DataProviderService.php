<?php

namespace AppBundle\Service;

use AppBundle\Provider\DataHazardProvider;

class DataProviderService
{

    /** @var string $kernel*/
    private $rootDir;

    private const PROVIDER_CODE_MAPPING = [
        'usgsp' => 'USGS'
    ];

    /**
     * DataProviderService constructor.
     * @param string $rootDir
     */
    public function __construct(string $rootDir)
    {
        $this->$rootDir = $rootDir;
    }

    public function getProviderByCode(string $code): ?DataHazardProvider
    {
        if ($prefix = self::PROVIDER_CODE_MAPPING[$code]) {
            $className = $this->rootDir . '/../src/AppBundle/Provider/' . $prefix . 'Provider';
            try {
                $provider = new $className();
                return is_subclass_of($provider, 'DataHazardProvider') ? $provider : NULL;
            } catch (\Exception $e) {
                echo 'ceva';
            }
        }
        return NULL;
    }
}