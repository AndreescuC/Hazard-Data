<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

//TODO: add repo class
/**
 * @ORM\Entity(repositoryClass="")
 * @ORM\Table(name="client_user")
 */
class ClientUser implements UserInterface, EquatableInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", name="username")
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(type="string", name="password")
     */
    private $password;

    /**
     * @ORM\Column(type="string", name="user_apikey")
     */
    private $userAPIKey;

    /**
     * @var string
     * @ORM\Column(type="string", name="first_name")
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(type="string", name = "last_name")
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(type="string", name = "email")
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", name = "home_town")
     */
    private $homeTown;

    /**
     * @var double
     * @ORM\Column(type="string", name = "trust_level")
     */
    private $trustLevel;

    /**
     * @var Feedback[] | ArrayCollection
     */
    private $feedbacks;

    private $salt;
    private $roles;

    /**
     * ClientUser security constructor.
     * @param string $username
     * @param string $password
     * @param string $salt
     * @param array $roles
     */
    public function __construct(string $username, string $password, string $salt, array $roles = [])
    {
        $this->username = $username;
        $this->password = $password;
        $this->salt = $salt;
        $this->roles = $roles;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return ClientUser
     */
    public function setUsername(string $username): ClientUser
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return ClientUser
     */
    public function setFirstName(string $firstName): ClientUser
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return ClientUser
     */
    public function setLastName(string $lastName): ClientUser
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @return mixed
     */
    public function getUserAPIKey()
    {
        return $this->userAPIKey;
    }

    /**
     * @param mixed $userAPIKey
     */
    public function setUserAPIKey($userAPIKey)
    {
        $this->userAPIKey = $userAPIKey;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getHomeTown(): string
    {
        return $this->homeTown;
    }

    /**
     * @param string $homeTown
     */
    public function setHomeTown(string $homeTown)
    {
        $this->homeTown = $homeTown;
    }

    /**
     * @return float
     */
    public function getTrustLevel(): float
    {
        return $this->trustLevel;
    }

    /**
     * @param float $trustLevel
     */
    public function setTrustLevel(float $trustLevel)
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
    public function setFeedbacks($feedbacks)
    {
        $this->feedbacks = $feedbacks;
    }

    /**
     * @param Feedback[]|ArrayCollection $feedbacks
     */
    public function addFeedback($feedbacks)
    {
        $this->feedbacks = $feedbacks;
    }

    public function isEqualTo(UserInterface $user)
    {
        return $user instanceof ClientUser &&
            $this->password === $user->getPassword() &&
            $this->salt === $user->getSalt() &&
            $this->username === $user->getUsername();
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

}