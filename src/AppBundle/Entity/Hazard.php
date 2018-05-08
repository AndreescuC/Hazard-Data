<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="hazard")
 */
class Hazard
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", name="name", unique=true)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="text", name="safety_measures")
     */
    private $safetyMeasures;

    /**
     * @var Warning[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Warning", mappedBy="hazard")
     */
    private $warnings;

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
    public function getSafetyMeasures(): string
    {
        return $this->safetyMeasures;
    }

    /**
     * @param string $safetyMeasures
     */
    public function setSafetyMeasures(string $safetyMeasures)
    {
        $this->safetyMeasures = $safetyMeasures;
    }

    /**
     * @return Warning[]|ArrayCollection
     */
    public function getWarnings()
    {
        return $this->warnings;
    }

    /**
     * @param Warning[]|ArrayCollection $warnings
     */
    public function setWarnings($warnings)
    {
        $this->warnings = $warnings;
    }

}