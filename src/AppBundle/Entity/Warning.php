<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WarningRepository")
 * @ORM\Table(name="warning")
 */
class Warning
{
    const POPULATION_LOW = 1;
    const POPULATION_MEDIUM = 2;
    const POPULATION_HIGH = 3;

    const STATUS_PENDING = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_DUPLICATED = 3;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", name="ext_id")
     */
    private $extId;

    /**
     * @var Hazard
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Hazard", inversedBy="warnings")
     * @ORM\JoinColumn(name="hazard_type", referencedColumnName="id")
     */
    private $hazard;

    /**
     * @ORM\Column(type="string", name="status")
     */
    private $status;

    /**
     * @ORM\Column(type="float", name="location_lat")
     */
    private $locationLat;

    /**
     * @ORM\Column(type="float", name="location_long")
     */
    private $locationLong;

    /**
     * @ORM\Column(type="integer", name="population")
     */
    private $population;

    /**
     * @ORM\Column(type="float", name="trust_level")
     */
    private $trustLevel;

    /**
     * @var Feedback[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Feedback", mappedBy="warning")
     */
    private $feedbacks;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getExtId(): string
    {
        return $this->extId;
    }

    /**
     * @param string $extId
     */
    public function setExtId(string $extId)
    {
        $this->extId = $extId;
    }

    /**
     * @return Hazard
     */
    public function getHazard(): Hazard
    {
        return $this->hazard;
    }

    /**
     * @param Hazard $hazard
     */
    public function setHazard(Hazard $hazard)
    {
        $this->hazard = $hazard;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getLocationLat()
    {
        return $this->locationLat;
    }

    /**
     * @param mixed $locationLat
     */
    public function setLocationLat($locationLat)
    {
        $this->locationLat = $locationLat;
    }

    /**
     * @return mixed
     */
    public function getLocationLong()
    {
        return $this->locationLong;
    }

    /**
     * @param mixed $locationLong
     */
    public function setLocationLong($locationLong)
    {
        $this->locationLong = $locationLong;
    }

    /**
     * @return mixed
     */
    public function getPopulation(): int
    {
        return $this->population;
    }

    /**
     * @param mixed $population
     */
    public function setPopulation($population)
    {
        $this->population = $population;
    }

    /**
     * @return mixed
     */
    public function getTrustLevel()
    {
        return $this->trustLevel;
    }

    /**
     * @param mixed $trustLevel
     */
    public function setTrustLevel($trustLevel)
    {
        $this->trustLevel = $trustLevel;
    }

    /**
     * @return Feedback[]|ArrayCollection
     */
    public function getFeedbacks()
    {
        return $this->feedbacks;
    }

    /**
     * @param Feedback[]|ArrayCollection $feedbacks
     */
    public function setFeedbacks($feedbacks): void
    {
        $this->feedbacks = $feedbacks;
    }

    static function getPopulationValues()
    {
        return [
            self::$POPULATION_LOW,
            self::$POPULATION_MEDIUM,
            self::$POPULATION_HIGH
        ];
    }

}