<?php

namespace App\Entity\Api;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Helper\AnnotationGroups as AG;

/**
 * Class Project
 * @package App\Entity\Api
 * @ORM\Entity()
 * @ApiResource(
 *     collectionOperations={
 *          "get",
 *          "post"
 *     },
 *     itemOperations={
 *          "get"={"normalization_context"={"groups"={AG::COUNTRY_READ}}},
 *          "put",
 *          "delete"
 *     }
 * )
 */
class Country
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({AG::COUNTRY_READ})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, unique=true)
     * @Groups({AG::COUNTRY_READ})
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=3, unique=true)
     */
    private $isoCode;

    /**
     * Many Tasks have one Project.
     * @ORM\ManyToOne(targetEntity="App\Entity\Api\Continent", inversedBy="countries")
     * @ORM\JoinColumn(name="continent_id", referencedColumnName="id")
     */
    private $continent;

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
    public function getIsoCode(): string
    {
        return $this->isoCode;
    }

    /**
     * @param string $isoCode
     */
    public function setIsoCode(string $isoCode): void
    {
        $this->isoCode = $isoCode;
    }

    /**
     * @return mixed
     */
    public function getContinent()
    {
        return $this->continent;
    }

    /**
     * @param mixed $continent
     */
    public function setContinent($continent): void
    {
        $this->continent = $continent;
    }
}