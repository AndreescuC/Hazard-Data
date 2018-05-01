<?php
/**
 * Created by PhpStorm.
 * User: georgiana.besea
 * Date: 04/17/18
 * Time: 13:45
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="warning")
 */
class Warning
{
    static $POPULATION_LOW = 1;
    static $POPULATION_MEDIUM = 2;
    static $POPULATION_HIGH = 3;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(type="integer", name="ext_id")
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
     * @return Hazard
     */
    public function getHazard()
    {
        return $this->hazard;
    }

    /**
     * @param Hazard $hazardType
     */
    public function setHazardType($hazardType)
    {
        $this->hazard = $hazardType;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return float
     */
    public function getRadius()
    {
        return $this->radius;
    }

    /**
     * @param float $radius
     */
    public function setRadius($radius)
    {
        $this->radius = $radius;
    }

    /**
     * @return integer
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * @param integer $population
     */
    public function setPopulation($population)
    {
        $this->population = $population;
    }

    /**
     * @return string
     */
    public function getTrustLevel()
    {
        return $this->trustLevel;
    }

    /**
     * @param string $trustLevel
     */
    public function setTrustLevel($trustLevel)
    {
        $this->trustLevel = $trustLevel;
    }


}