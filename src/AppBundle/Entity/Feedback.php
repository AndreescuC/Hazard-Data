<?php
/**
 * Created by PhpStorm.
 * User: georgiana.besea
 * Date: 04/17/18
 * Time: 13:34
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="feedback")
 */
class Feedback
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var ClientUser
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ClientUser", inversedBy="feedbacks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var Warning
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Warning", inversedBy="feedbacks")
     * @ORM\JoinColumn(name="warning_id", referencedColumnName="id")
     */
    private $warning;

    /**
     * @ORM\Column(type="text", name="content")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", name="date_created")
     */
    private $dateCreated;

    /**
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return ClientUser
     */
    public function getUser(): ClientUser
    {
        return $this->user;
    }

    /**
     * @param ClientUser $user
     */
    public function setUser(ClientUser $user)
    {
        $this->user = $user;
    }

    /**
     * @return Warning
     */
    public function getWarning(): Warning
    {
        return $this->warning;
    }

    /**
     * @param Warning $warning
     */
    public function setWarning(Warning $warning)
    {
        $this->warning = $warning;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreated(): \DateTime
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime $dateCreated
     */
    public function setDateCreated(\DateTime $dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

}