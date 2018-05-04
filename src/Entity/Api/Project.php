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
}