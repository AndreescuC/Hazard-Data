<?php

namespace AppBundle\Service;

use AppBundle\Provider\DataHazardProvider;

class DataProviderService
{
    private const PROVIDER_CODE_MAPPING = [
        'usgs' => 'USGS'
    ];

    public function getProviderByCode(string $code): ?DataHazardProvider
    {
        if ($prefix = self::PROVIDER_CODE_MAPPING[$code]) {
            $className = "AppBundle\Provider\\" . $prefix . 'Provider';
            try {
                $provider = new $className();
                return is_subclass_of($provider, 'AppBundle\Provider\DataHazardProvider') ? $provider : NULL;
            } catch (\Exception $e) {
                echo 'ceva';
            }
        }
        return NULL;
    }
}