<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DataProviderRepository")
 * @ORM\Table(name="data_provider")
 */
class DataProvider
{

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    const SCOPE_EARTHQUAKE = "Earthquakes";
    const SCOPE_WEATHER = "Weather";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", name="name")
     */
    private $name;

    /**
     * @var int
     * @ORM\Column(type="integer", name="status")
     */
    private $status;

    /**
     * @var string
     * @ORM\Column(type="string", name="code")
     */
    private $code;

    /**
     * @var string
     * @ORM\Column(type="string", name="scope")
     */
    private $scope;

    /**
     * @var string
     * @ORM\Column(type="text", name="query_url")
     */
    private $queryURL;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     */
    public function setScope(string $scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return string
     */
    public function getQueryURL(): string
    {
        return $this->queryURL;
    }

    /**
     * @param string $queryURL
     */
    public function setQueryURL(string $queryURL)
    {
        $this->queryURL = $queryURL;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }
}