<?php

namespace Randomovies\Dto\Mapper;

use Randomovies\Dto\MovieDto;
use Randomovies\Entity\Movie;
use Randomovies\Entity\Tag;

class MovieMapper extends Mapper
{
	/**
	 * @var array $savedTags
	 */
	private $savedTags;
	
	public function __construct(array $savedTags = [])
	{		
		$this->savedTags = $savedTags;
	}
	
    /**
     * @param MovieDto $movieDto
     * @param Movie $movie
     */
    public function map($movieDto, $movie)
    {
		$this->setCorrespondingTags($movieDto);
        parent::map($movieDto, $movie);
    }
	
	public function setSavedTags(array $savedTags)
	{
		$this->savedTags = $savedTags;		
	}
	
	private function setCorrespondingTags(&$movieDto)
	{
		$tags = explode(',', $movieDto->tagsAsString);
		foreach ($tags as $tag) {						
			$matches = array_filter($this->savedTags, function($savedTag) use ($tag) {				
				return $savedTag->getName() === ucfirst(strtolower(trim($tag)));
			});
						
			if (count($matches) > 0) {
				$movieDto->tags->add(array_values($matches)[0]);				
			} else {				
				$newTag = new Tag();
				$newTag->setName($tag);				
				$movieDto->tags->add($newTag);
			}
		}		
	}
}
