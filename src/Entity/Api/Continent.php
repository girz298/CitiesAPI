<?php


namespace App\Entity\Api;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use App\Helper\AnnotationGroups as AG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Project
 * @package App\Entity\Api
 * @ORM\Entity()
 * @ApiResource(
 *     attributes={"pagination_items_per_page"=999999},
 *     collectionOperations={
 *          "get"={"normalization_context"={"groups"={AG::CONTINENT_READ, AG::COUNTRY_READ}}},
 *          "post"
 *     },
 *     itemOperations={
 *          "get",
 *          "put",
 *          "delete"
 *     }
 * )
 */
class Continent
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({AG::CONTINENT_READ})
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(type="string", length=40, unique=true)
     * @Groups({AG::CONTINENT_READ})
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=40)
     * @Groups({AG::CONTINENT_READ})
     */
    private $code;

    /**
     * @ApiSubresource()
     * @var ArrayCollection $tasks One Project has many Tasks
     * @ORM\OneToMany(targetEntity="App\Entity\Api\Country", mappedBy="continent", cascade={"remove"})
     * @Groups({AG::CONTINENT_READ})
     */
    private $countries;

    public function __construct()
    {
        $this->countries = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
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
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return ArrayCollection
     */
    public function getCountries(): ?Collection
    {
        return $this->countries;
    }

    /**
     * @param ArrayCollection $countries
     */
    public function setCountries(ArrayCollection $countries): void
    {
        $this->countries = $countries;
    }
}