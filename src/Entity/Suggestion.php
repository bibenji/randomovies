<?php

namespace Randomovies\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Suggestion
 *
 * @ORM\Table(name="suggestion")
 * @ORM\Entity() 
 * @UniqueEntity(
 *     fields={"movieTitle", "user"},
 *     message="Vous avez déjà suggéré ce film."
 * )
 */
class Suggestion
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string")
     * @ORM\Id
     */
    private $id;

    /** 
     * @var string
     *      
     * @ORM\Column(name="movie_title", type="string", length=255)
     */
    private $movieTitle;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Randomovies\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $suggestedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $treatedAt;
    
    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->suggestedAt = new \DateTime();
        $this->treatedAt = NULL;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
        
    /**
     * @return string
     */
    public function getMovieTitle(): ?string
    {
    	return $this->movieTitle;
    }
    
    /**
     * @param string $movieTitle
     */
    public function setMovieTitle(string $movieTitle)
    {
    	$this->movieTitle = $movieTitle;
    }    

    /**
     * @return User
     */
    public function getUser(): User
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
     * @return mixed
     */
    public function getSuggestedAt()
    {
        return $this->suggestedAt;
    }

    /**
     * @param mixed $suggestedAt
     */
    public function setSuggestedAt($suggestedAt)
    {
        $this->suggestedAt = $suggestedAt;
    }

    /**
     * @return mixed
     */
    public function getTreatedAt()
    {
        return $this->treatedAt;
    }

    /**
     * @param mixed $treatedAt
     */
    public function setTreatedAt($treatedAt)
    {
        $this->treatedAt = $treatedAt;
    }
}
