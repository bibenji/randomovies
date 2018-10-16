<?php

namespace Randomovies\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Role
 *
 * @ORM\Table(name="review")
 * @ORM\Entity()
 */
class Review
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string")
     * @ORM\Id     
     */
    private $id;

    /**
     * @var Movie
     *
     * @ORM\ManyToOne(targetEntity="Randomovies\Entity\Movie", inversedBy="reviews")
     * @ORM\JoinColumn(name="movie_id", referencedColumnName="id")
     */
    private $movie;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Randomovies\Entity\User", inversedBy="reviews")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;

    /**
     * @var string
     *
     * @ORM\Column(name="review", type="text", nullable=true)
     */
    private $review;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    
    /** 
     * @var boolean
     * 
     * @ORM\Column("main", type="boolean")
     */
    private $main;
    
    /**
     * Review constructor
     */
    public function __construct()
    {
    	$this->id = Uuid::uuid4()->toString();
    	$this->rating = 5;
    	$this->createdAt = new \DateTime();
    	$this->main = FALSE;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return Movie
     */
    public function getMovie(): Movie
    {
        return $this->movie;
    }

    /**
     * @param Movie $movie
     */
    public function setMovie(Movie $movie)
    {
        $this->movie = $movie;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
    
    /**
     * Set rating
     *
     * @param integer $rating
     *
     * @return Movie
     */
    public function setRating($rating)
    {
    	$this->rating = $rating;
    	
    	return $this;
    }
    
    /**
     * Get rating
     *
     * @return int
     */
    public function getRating()
    {
    	return $this->rating;
    }

    /**
     * @return string
     */
    public function getReview(): ?string
    {
        return $this->review;
    }

    /**
     * @param string $review
     */
    public function setReview(string $review)
    {
        $this->review = $review;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
    	return $this->createdAt;
    }
    
    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
    	$this->createdAt = $createdAt;
    }
    
    /**     
     * @return boolean
     */
    public function isMain()
    {
    	return $this->main;
    }
    
    /**     
     * @param boolean $main
     */
    public function setMain($main)
    {
    	$this->main = $main;    	
    }
}
