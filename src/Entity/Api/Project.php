<?php
/**
 * Created by PhpStorm.
 * User: s.rodionov
 * Date: 04.05.18
 * Time: 15:19
 */

namespace App\Entity\Api;

use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Security\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Project
 * @package App\Entity\Api
 * @ORM\Entity()
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"normalization_context"={"groups"={"read"}}},
 *          "post"={
 *              "normalization_context"={"groups"={"post_read"}},
 *              "denormalization_context"={"groups"={"write"}}
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"read"}},
 *              "access_control"="object.getUser().getId() == user.getId()"
 *          },
 *          "put"={
 *              "normalization_context"={"groups"={"post_read"}},
 *              "denormalization_context"={"groups"={"write"}}
 *          },
 *          "delete"
 *     }
 * )
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"read", "post_read"})
     */
    private $id;

    /**
     * @ApiSubresource()
     * @var ArrayCollection $tasks One Project has many Tasks
     * @ORM\OneToMany(targetEntity="App\Entity\Api\Task", mappedBy="project", cascade={"remove"})
     * @Groups({"read"})
     */
    private $tasks;

    /**
     * @var string $title A name property - this is the name of the Project.
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank(message="Project name couldn't be empty")
     * @Assert\Length(min="5", minMessage="Project name couldn't be less than 5 characters")
     * @Groups({"read", "write", "post_read"})
     */
    private $name;

    /**
     * Many Projects have one User.
     * @ORM\ManyToOne(targetEntity="App\Entity\Security\User", inversedBy="projects")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getTasks()
    {
        return $this->tasks;
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
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param mixed $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser() : User
    {
        return $this->user;
    }
}
