<?php

namespace AppBundle\Provider;

class USGSProvider extends DataHazardProvider
{

    /**
     * Format for array of events:
     * [
     *   'ext_id' => '',
     *   'provider' => '',
     *   'name' => '',
     *   'type' => '',
     *   'location' => [
     *      'lat' => '',
     *      'long' => ''
     *   ],
     *   'details' => []
     * ]
     *
     * @return array|null
     */
    public function getFormattedEvents(): ?array
    {
        $data = $this->getRequestResponse();
        if (!$data) {
            return NULL;
        }
        return $data;
    }
}