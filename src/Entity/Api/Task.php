<?php

namespace App\Entity\Api;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Task
 * @package App\Entity\Api
 * @ORM\Entity()
 * @ApiResource(attributes={"pagination_items_per_page"=5})
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
     */
    private $title;

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

}