<?php

namespace Randomovies\Entity;

class MovieSearch
{
    protected $title;
    protected $synopsis;
    protected $genre;

    protected $yearFrom;
    protected $yearTo;

	protected $durationFrom;
	protected $durationTo;
	
	protected $ratedMin;
	protected $ratedMax;

    protected $actors;
    protected $director;

    public function __construct()
    {			
        $this->yearFrom = new \DateTime('1900-01-01');
        $this->yearTo = new \DateTime();				
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

    public function getSynopsis()
    {
        return $this->synopsis;
    }

    public function setSynopsis($synopsis)
    {
        $this->synopsis = $synopsis;
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

    public function getYearTo()
    {
        return $this->yearTo;
    }

    public function clearYears(){
        $this->yearFrom = null;
        $this->yearTo = null;
    }

    /**
     * @return mixed
     */
    public function getDurationFrom()
    {
        return $this->durationFrom;
    }

    /**
     * @param mixed $durationFrom
     */
    public function setDurationFrom($durationFrom)
    {
        $this->durationFrom = $durationFrom;
    }

    /**
     * @return mixed
     */
    public function getDurationTo()
    {
        return $this->durationTo;
    }

    /**
     * @param mixed $durationTo
     */
    public function setDurationTo($durationTo)
    {
        $this->durationTo = $durationTo;
    }

    /**
     * @return mixed
     */
    public function getRatedMin()
    {
        return $this->ratedMin;
    }

    /**
     * @param mixed $ratedMin
     */
    public function setRatedMin($ratedMin)
    {
        $this->ratedMin = $ratedMin;
    }

    /**
     * @return mixed
     */
    public function getRatedMax()
    {
        return $this->ratedMax;
    }

    /**
     * @param mixed $ratedMax
     */
    public function setRatedMax($ratedMax)
    {
        $this->ratedMax = $ratedMax;
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

	public function getDirector()
    {
        return $this->director;
    }

    public function setDirector($director)
    {
        $this->director = $director;
        return $this;
    }
}