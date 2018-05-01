<?php
/**
 * Created by PhpStorm.
 * User: georgiana.besea
 * Date: 05/01/18
 * Time: 18:06
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="settings")
 */
class Settings
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="credibility_threshold")
     */
    private $credibilityThreshold;

    /**
     * @ORM\Column(type="integer", name="maximum_distance")
     */
    private $maximumDistance;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return integer
     */
    public function getCredibilityThreshold()
    {
        return $this->credibilityThreshold;
    }

    /**
     * @param integer $credibilityThreshold
     */
    public function setCredibilityThreshold($credibilityThreshold)
    {
        $this->credibilityThreshold = $credibilityThreshold;
    }

    /**
     * @return integer
     */
    public function getMaximumDistance()
    {
        return $this->maximumDistance;
    }

    /**
     * @param integer $maximumDistance
     */
    public function setMaximumDistance($maximumDistance)
    {
        $this->maximumDistance = $maximumDistance;
    }


}