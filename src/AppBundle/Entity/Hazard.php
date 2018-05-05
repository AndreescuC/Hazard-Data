<?php
/**
 * Created by PhpStorm.
 * User: georgiana.besea
 * Date: 04/17/18
 * Time: 15:01
 */

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


}