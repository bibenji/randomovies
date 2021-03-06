<?php

namespace Randomovies\Dto;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

class MovieDto
{
    /**
     * @var string
     * @Assert\NotBlank(message="Colonne A : Ne doit pas être vide")
     */
    public $title;

    /**
     * @var string
     */
    public $director;

    /**
     * @var string
     */
    public $actors;

    /**
     * @var int
     * @Assert\NotBlank(message="Colonne D : Ne doit pas être vide")
     */
    public $year;

    /**
     * @var int
     * @Assert\NotBlank(message="Colonne E : Ne doit pas être vide")
     */
    public $duration;

    /**
     * @var string
     */
    public $synopsis;

    /**
     * @var int
     * @Assert\NotBlank(message="Colonne G : Ne doit pas être vide")
     */
    public $rating;

    /**
     * @var string
     */
    public $critique;

    /**
     * @var string
     * @Assert\NotBlank(message="Colonne I : Ne doit pas être vide")
     */
    public $genre;

    /**
     * @var string
     */
    public $poster;

    /**
     * @var Collection
     */
    public $roles;

    /**
     * @var Collection
     */
    public $comments;

    /**
     * @var Collection
     */
    public $tags;
	
	/**
	 * @var string
	 */
	public $tagsAsString;

    public function __construct(array $row = [])
    {
		$this->tags = new ArrayCollection();
		
        $this->title = $row[0];
        $this->director = $row[2];
        $this->actors = $row[1];
        $this->year = $row[3];
        $this->duration = $row[4];
        $this->synopsis = $row[5];
        $this->rating = $row[6];
        $this->critique = $row[7];
        $this->genre = $row[8];				
        $this->tagsAsString = $row[9];		
		$this->poster = $row[10];
    }	
}
