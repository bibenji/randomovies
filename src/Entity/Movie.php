<?php

namespace Randomovies\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Movie
 *
 * @ORM\Table(name="movie")
 * @ORM\Entity(repositoryClass="Randomovies\Repository\MovieRepository")
 */
class Movie
{
    const ACTORS_AS_STRING = TRUE;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="director", type="string", length=255)
     */
    private $director;

    /**
     * @var string
     *
     * @ORM\Column(name="actors", type="string", length=255, nullable=true)
     */
    private $actors;

    /**
     * @var int
     *
     * @ORM\Column(name="year", type="integer")
     */
    private $year;

    /**
     * @var int
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="synopsis", type="text")
     */
    private $synopsis;

    /**
     * @ORM\OneToMany(targetEntity="Randomovies\Entity\Review", mappedBy="movie", cascade={"all"})
     */
    private $reviews;

    /**
     * @var string
     *
     * @ORM\Column(name="genre", type="string", length=255)
     */
    private $genre;

    /**
     * @var string
     *
     * @ORM\Column(name="poster", type="string", length=255, nullable=true)
     */
    private $poster;

    /**
     * @ORM\OneToMany(targetEntity="Randomovies\Entity\Role", mappedBy="movie", cascade={"all"}, orphanRemoval=true)
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="Randomovies\Entity\Comment", mappedBy="movie", cascade={"remove"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $comments;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Randomovies\Entity\Tag", inversedBy="movies", cascade={"persist"})
     */
    private $tags;
    
    /**
     * @ORM\OneToOne(targetEntity="Randomovies\Entity\Suggestion", cascade={"all"})     
     */
    private $suggestion;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Movie
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set director
     *
     * @param string $director
     *
     * @return Movie
     */
    public function setDirector($director)
    {
        $this->director = $director;

        return $this;
    }

    /**
     * Get director
     *
     * @return string
     */
    public function getDirector()
    {
    	if (!($this->roles->isEmpty())) {
    		foreach($this->roles as $role) {
    			if (Role::ROLE_REALISATOR === $role->getRole()) {
    				return $role->getPerson()->getFullname();
    			}
    		}
    	}    	
        return $this->director;
    }
    
    /**
     * Get director person
     * 
     * @return Person
     */
    public function getDirectorPerson(): ?Person
    {
        if (!($this->roles->isEmpty())) {
            foreach($this->roles as $role) {
                if (Role::ROLE_REALISATOR === $role->getRole()) {
                    return $role->getPerson();
                }
            }
        }
        return NULL;
    }

    /**
     * Set actors
     *
     * @param string $actors
     *
     * @return Movie
     */
    public function setActors($actors)
    {
        $this->actors = $actors;

        return $this;
    }

    /**
     * Get actors
     *
     * @return string
     */
    public function getActors($asString = FALSE)
    {        
    	if (!$asString && !$this->roles->isEmpty()) {
    		$actors = [];
    		foreach ($this->roles as $role) {    			
    			if (Role::ROLE_ACTOR === $role->getRole()) {
    				$actors[] = $role->getPerson()->getFullname();
    			}
    		}
    		return implode(', ', $actors);
    	}
        return $this->actors;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return Movie
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Movie
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set synopsis
     *
     * @param string $synopsis
     *
     * @return Movie
     */
    public function setSynopsis($synopsis)
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    /**
     * Get synopsis
     *
     * @return string
     */
    public function getSynopsis()
    {
        return $this->synopsis;
    }

    /**
     * Get main rating
     *
     * @return int
     */
    public function getRating()
    {
    	foreach ($this->reviews as $review) {
    		if ($review->isMain()) {
    			return $review->getRating();
    		}
    	}
    }
    
    /**
     * Get main review
     * 
     * @return string
     */
    public function getReview()
    {
    	foreach ($this->reviews as $review) {
    		if ($review->isMain()) {
    			return $review;
    		}
    	}
    }
    
    public function getOtherReviews()
    {
        $reviews = [];
        foreach ($this->reviews as $review) {
            if (!$review->isMain()) {
                $reviews[] = $review;
            }
        }
        return $reviews;        
    }
    
    /**
     * @param Review $review
     * @return $this
     */
    public function addReview(Review $review)
    {
    	$review->setMovie($this);
//         $this->roles[] = $role;
    	$this->reviews->add($review);
    	return $this;
    }
    
    /**
     * @param Review $review
     */
    public function removeReview(Review $review)
    {
    	$this->reviews->removeElement($review);
    }
    
    /**
     * @return ArrayCollection
     */
    public function getReviews()
    {
    	return $this->reviews;
    }
    
    public function setReviews($reviews)
    {
    	$this->reviews = $reviews;
    }
    
    /**
     * Set genre
     *
     * @param string $genre
     *
     * @return Movie
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set poster
     *
     * @param string $poster
     *
     * @return Movie
     */
    public function setPoster($poster)
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * Get poster
     *
     * @return string
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * @param Role $role
     * @return $this
     */
    public function addRole(Role $role)
    {
        $role->setMovie($this);
//        $this->roles[] = $role;
        $this->roles->add($role);
        return $this;
    }

    /**
     * @param Role $role
     */
    public function removeRole(Role $role)
    {
        $this->roles->removeElement($role);
    }

    /**
     * @return ArrayCollection
     */
    public function getRoles($roleRole = NULL)
    {           
        if ($roleRole === Role::ROLE_ACTOR || $roleRole === Role::ROLE_REALISATOR) {
            $roles = [];
            foreach ($this->roles  as $role) {
                if ($roleRole === $role->getRole()) $roles[] = $role;
            }
        } else {
            $roles = $this->roles;
        }

        return $roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function addComment(Comment $comment)
    {
        $comment->setMovie($this);
//        $this->roles[] = $role;
        $this->comments->add($comment);
        return $this;
    }

    /**
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @param Tag $tag
     * @return $this
     */
    public function addTag(Tag $tag)
    {
        if (!$this->tags->contains($tag)) {
//            $tag->setMovies($this);
            $this->tags->add($tag);
        }

        return $this;
    }

    /**
     * @param Tag $tag
     * @return $this
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->remove($tag);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Collection $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }
    
    public function getSuggestion()
    {
        return $this->suggestion;
    }
    
    public function setSuggestion(Suggestion $suggestion)
    {
        $this->suggestion = $suggestion;
    }
}
