<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

//TODO: add repo class
/**
 * @ORM\Entity(repositoryClass="")
 */
class ClientUser implements UserInterface, EquatableInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
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
     * @var string
     * @ORM\Column(type="string", name="first_name")
     */
    private $firstName;

    private $salt;
    private $roles;

    /**
     * ClientUser constructor.
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
     * @var string
     * @ORM\Column(type="string", name="last_name")
     */
    private $lastName;

    //TODO: add reports, credibility and other attributes

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