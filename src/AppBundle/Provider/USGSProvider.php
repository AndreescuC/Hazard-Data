<?php

namespace AppBundle\Provider;

class USGSProvider extends DataHazardProvider
{

    /**
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