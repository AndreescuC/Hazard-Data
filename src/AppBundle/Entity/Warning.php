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
    const POPULATION_LOW = 0;
    const POPULATION_MEDIUM = 1;
    const POPULATION_HIGH = 2;

    const GRAVITY_LOW = 0;
    const GRAVITY_MEDIUM = 1;
    const GRAVITY_HIGH = 2;
    const GRAVITY_VERY_HIGH = 3;

    const STATUS_PENDING = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_CONFIRMED_TRIGGER = 3;
    const STATUS_DUPLICATED = 4;

    const PROVIDER_TYPE_USER = 1;
    const PROVIDER_TYPE_API = 2;

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
     * @ORM\Column(type="integer", name="gravity")
     */
    private $gravity;

    /**
     * @ORM\Column(type="float", name="trust_level")
     */
    private $trustLevel;

    /**
     * @ORM\Column(type="datetime", name="date_created")
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime")
     */
    private $dateModified;

    /**
     * @var Feedback[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Feedback", mappedBy="warning")
     */
    private $feedbacks;


    public function __construct()
    {
        $this->setDateCreated(new \DateTime());
        if ($this->getDateModified() == null) {
            $this->setDateModified(new \DateTime());
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateModifiedDatetime()
    {
        $this->setDateModified(new \DateTime());
    }

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
    public function getGravity()
    {
        return $this->gravity;
    }

    /**
     * @param mixed $gravity
     */
    public function setGravity($gravity)
    {
        $this->gravity = $gravity;
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
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateModified(): ?\DateTime
    {
        return $this->dateModified;
    }

    /**
     * @param \DateTime $dateModified
     */
    public function setDateModified(\DateTime $dateModified)
    {
        $this->dateModified = $dateModified;
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
            self::POPULATION_LOW,
            self::POPULATION_MEDIUM,
            self::POPULATION_HIGH
        ];
    }

    static function getGravityValues()
    {
        return [
            self::GRAVITY_LOW,
            self::GRAVITY_MEDIUM,
            self::GRAVITY_HIGH,
            self::GRAVITY_VERY_HIGH
        ];
    }

}