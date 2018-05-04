<?php
/**
 * Created by PhpStorm.
 * User: s.rodionov
 * Date: 04.05.18
 * Time: 15:19
 */

namespace App\Entity\Api;

use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Project
 * @package App\Entity\Api
 * @ORM\Entity()
 * @ApiResource(attributes={"pagination_items_per_page"=5})
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var ArrayCollection $tasks One Project has many Tasks
     * @ORM\OneToMany(targetEntity="App\Entity\Api\Task", mappedBy="project")
     * @ApiSubresource()
     */
    private $tasks;

    /**
     * @var string $title A name property - this is the name of the Project.
     *
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank(message="Project name couldn't be empty")
     * @Assert\Length(min="5", minMessage="Project name couldn't be less than 5 characters")
     */
    private $name;

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
}
