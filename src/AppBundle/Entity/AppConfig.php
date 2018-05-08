<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AppConfigRepository")
 * @ORM\Table(name="app_config")
 */
class AppConfig
{
    const CONFIG_RADIUS = 'warning.radius';
    const CONFIG_TRUST_THRESHOLD = 'warning.trust';

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
     * @ORM\Column(type="string", name="type")
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(type="text", name="description")
     */
    private $description;

    /**
     * @var int
     * @ORM\Column(type="integer", name="display_position")
     */
    private $displayPosition;


    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getDisplayPosition(): int
    {
        return $this->displayPosition;
    }

    public function setDisplayPosition(int $displayPosition)
    {
        $this->displayPosition = $displayPosition;
    }
}