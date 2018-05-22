<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="parameters")
 */
class Parameters
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /**
     * @var float
     * @ORM\Column(type="float", name="warning_radius")
     */
    private $warningRadius;

    /**
     * @var float
     * @ORM\Column(type="float", name="warning_tolerance")
     */
    private $warningTolerance;

    /**
     * @var float
     * @ORM\Column(type="float", name="api_key_generation_min_value")
     */
    private $apiKeyGenerationMinValue;

    /**
     * @var float
     * @ORM\Column(type="float", name="api_key_generation_max_value")
     */
    private $apiKeyGenerationMaxValue;

    /**
     * @var float
     * @ORM\Column(type="float", name="api_key_generation_max_attempts")
     */
    private $apiKeyGenerationMaxAttempts;

    /**
     * @var string
     * @ORM\Column(type="string", name="firabase_server_key")
     */
    private $firabaseServerKey;

    /**
     * @return float
     */
    public function getWarningRadius(): float
    {
        return $this->warningRadius;
    }

    /**
     * @param float $warningRadius
     */
    public function setWarningRadius(float $warningRadius)
    {
        $this->warningRadius = $warningRadius;
    }

    /**
     * @return float
     */
    public function getWarningTolerance(): float
    {
        return $this->warningTolerance;
    }

    /**
     * @param float $warningTolerance
     */
    public function setWarningTolerance(float $warningTolerance)
    {
        $this->warningTolerance = $warningTolerance;
    }

    /**
     * @return float
     */
    public function getApiKeyGenerationMinValue(): float
    {
        return $this->apiKeyGenerationMinValue;
    }

    /**
     * @param float $apiKeyGenerationMinValue
     */
    public function setApiKeyGenerationMinValue(float $apiKeyGenerationMinValue)
    {
        $this->apiKeyGenerationMinValue = $apiKeyGenerationMinValue;
    }

    /**
     * @return float
     */
    public function getApiKeyGenerationMaxValue(): float
    {
        return $this->apiKeyGenerationMaxValue;
    }

    /**
     * @param float $apiKeyGenerationMaxValue
     */
    public function setApiKeyGenerationMaxValue(float $apiKeyGenerationMaxValue)
    {
        $this->apiKeyGenerationMaxValue = $apiKeyGenerationMaxValue;
    }

    /**
     * @return float
     */
    public function getApiKeyGenerationMaxAttempts(): float
    {
        return $this->apiKeyGenerationMaxAttempts;
    }

    /**
     * @param float $apiKeyGenerationMaxAttempts
     */
    public function setApiKeyGenerationMaxAttempts(float $apiKeyGenerationMaxAttempts)
    {
        $this->apiKeyGenerationMaxAttempts = $apiKeyGenerationMaxAttempts;
    }

    /**
     * @return string
     */
    public function getFirabaseServerKey(): string
    {
        return $this->firabaseServerKey;
    }

    /**
     * @param string $firabaseServerKey
     */
    public function setFirabaseServerKey(string $firabaseServerKey)
    {
        $this->firabaseServerKey = $firabaseServerKey;
    }
}