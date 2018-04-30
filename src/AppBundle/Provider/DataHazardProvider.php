<?php

namespace AppBundle\Provider;

use AppBundle\Entity\DataProvider;

abstract class DataHazardProvider
{
    /** @var DataProvider $providerEntiy */
    private $providerEntiy;

    /** @var string $query */
    private $query = NULL;

    /** @var  array $responseData */
    private $responseData = [];

    /** @var  bool $responseAvailable */
    protected $responseAvailable;

    /**
     * DataHazardProvider constructor.
     * @param DataProvider $entity
     */
    public function __construct(DataProvider $entity)
    {
        $this->providerEntiy = $entity;
        $this->query = $entity->getQueryURL();
        $this->responseAvailable = false;
    }

    /**
     * @param array $parameters
     * @return bool
     */
    public function setRequestParameters(array $parameters): bool
    {
        if (!$this->query) {
            return false;
        }

        $query = $this->query;
        foreach ($parameters as $parameter => $value) {
            $query .= '&' . $parameter . '=' . $value;
        }
        $this->query = $query;
        return true;
    }

    /**
     * @return bool
     */
    public function makeRequest(): bool
    {
        if (!$this->query) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->query);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        if (!$response) {
            return false;
        }
        $this->responseData = json_decode($response);
        $this->responseAvailable = true;
        return true;
    }

    /**
     * @return array|null
     */
    public function getRequestResponse(): ?array
    {
        if ($this->responseAvailable) {
            $this->responseAvailable = false;
            return $this->responseData;
        }
        return NULL;
    }

    public abstract function getFormattedEvents(): ?array;
}