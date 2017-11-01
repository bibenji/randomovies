<?php

namespace AppBundle\Entity;

class MovieSearch
{
    protected $title;
    protected $director;
    protected $actors;
	
	protected $yearFrom;
	protected $yearTo;
	
	// protected $durationFrom;
	// protected $durationTo;
	
	// protected $ratedMin;
	// protected $ratedMax;
	
	protected $genre;

    public function __construct()
    {			
        $this->yearFrom = new \DateTime('1900-01-01');
        $this->yearTo = new \DateTime();				
    }

    public function setYearFrom($yearFrom)
    {
        if ($yearFrom != "") {            
            $this->yearFrom = $yearFrom;
        }

        return $this;
    }

    public function getYearFrom()
    {
        return $this->yearFrom;
    }

    public function setYearTo($yearTo)
    {
        if ($yearTo != "") {            
            $this->yearTo = $yearTo;
        }

        return $this;
    }

    public function clearYears(){
        $this->yearFrom = null;
        $this->yearTo = null;
    }

    public function getYearTo()
    {
        return $this->yearTo;
    }

    public function getIsPublished()
    {
        return $this->isPublished;
    }

    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }
	
	public function getDirector()
    {
        return $this->director;
    }

    public function setDirector($director)
    {
        $this->director = $director;

        return $this;
    }
	
	public function getActors()
    {
        return $this->actors;
    }

    public function setActors($actors)
    {
        $this->actors = $actors;

        return $this;
    }
	
	public function getGenre()
    {
        return $this->genre;
    }

    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }
}