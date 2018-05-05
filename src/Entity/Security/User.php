<?php

namespace App\Entity\Security;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package App\Security
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, EquatableInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $salt = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $roles;

    /**
     * @var ArrayCollection $tasks One Project has many Tasks
     * @ORM\OneToMany(targetEntity="App\Entity\Api\Project", mappedBy="user")
     */
    private $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function isEqualTo(UserInterface $user)
    {
        if ($user->getUsername() == $this->getUsername()) {
            return true;
        }

        return false;
    }

    public function eraseCredentials()
    {
    }

    public function getRoles()
    {
        return [$this->roles];
    }

    public function setRoles(array $roles)
    {
        $this->roles = implode(',', $roles);
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        return '';
    }

    /**
     * @param string $salt
     */
    public function setSalt(string $salt): void
    {
        $this->salt = $salt;
    }

    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

}
