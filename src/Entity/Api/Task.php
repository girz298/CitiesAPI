<?php

namespace App\Entity\Api;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Task
 * @package App\Entity\Api
 * @ORM\Entity(repositoryClass="App\Repository\Api\TaskRepository")
 * @ApiResource(
 *     attributes={"pagination_items_per_page"=5},
 *     collectionOperations={
 *          "get",
 *          "post"={"denormalization_context"={"groups"={"write"}}}
 *     },
 *     itemOperations={
 *          "get"={"access_control"="object.getProject().getUser().getId() == user.getId()"},
 *          "put",
 *          "delete"
 *     })
 */
class Task
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $title A title property - this is the title of the Task.
     *
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Groups({"write"})
     */
    private $title;

    /**
     * Many Tasks have one Project.
     * @ORM\ManyToOne(targetEntity="App\Entity\Api\Project", inversedBy="tasks")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * @Groups({"write"})
     */
    private $project;

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getProject() : Project
    {
        return $this->project;
    }

    public function setProject(Project $project)
    {
        $this->project = $project;
    }
}
