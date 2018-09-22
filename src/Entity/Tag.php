<?php

namespace Randomovies\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="Randomovies\Repository\TagRepository")
 */
class Tag
{

    /**
     * @var string
     * @ORM\Column(name="id", type="string")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string")
     */
    protected $name;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Randomovies\Entity\Movie", mappedBy="tags")
     */
    protected $movies;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->movies = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return Collection
     */
    public function getMovies()
    {
        return $this->movies;
    }

    /**
     * @param Collection $movies
     */
    public function setMovies($movies)
    {
        $this->movies = $movies;
    }
}
