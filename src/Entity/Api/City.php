<?php

namespace App\Entity\Api;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use App\Helper\AnnotationGroups as AG;
use App\Entity\Api\Country;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Project
 * @package App\Entity\Api
 * @ORM\Entity()
 * @ApiResource(
 *     attributes={"pagination_items_per_page"=999999},
 *     subresourceOperations={
 *         "api_countries_cities_get_subresource"={
 *             "method"="GET",
 *             "normalization_context"={"groups"={AG::COUNTRY_CITY_SUBRESOURCE_READ}}
 *         }
 *     },
 *     collectionOperations={
 *          "post"
 *     },
 *     itemOperations={
 *          "get"={"normalization_context"={"groups"={AG::CITY_READ}}},
 *          "put",
 *          "delete"
 *     }
 * )
 */
class City
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({AG::CITY_READ, AG::COUNTRY_CITY_SUBRESOURCE_READ})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     * @Groups({AG::CITY_READ, AG::COUNTRY_CITY_SUBRESOURCE_READ})
     */
    private $name;

    /**
     * Many Tasks have one Project.
     * @ORM\ManyToOne(targetEntity="App\Entity\Api\Country", inversedBy="cities")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     */
    private $country;

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
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }
}