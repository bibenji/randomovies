<?php

namespace Randomovies\Tool;

class BaseETLParamsBuilder
{
	protected $params = [];
	
	public function getParams($json = FALSE)
	{	
		if ($json) {
			return json_encode($this->params);
		}
		
		return $this->params;		
	}
}

class ETLParamsBuilder extends BaseETLParamsBuilder
{	
	public function setIndex($index)
	{
		$this->params['index'] = $index;
	}
	
	public function setType($type)
	{
		$this->params['type'] = $type;
	}
	
	public function addQuery(Query $query)
	{
		$this->params['body']['query'] = $query->getParams();
	}	
	
	public function addSize($size)
	{
		$this->params['body']['size'] = $size;
	}
	
	public function addAggregation($aggregationName, $aggregationType, array $options)
	{
		$this->params['body']['aggregations'][$aggregationName][$aggregationType] = $options;
	}	
}

class Query extends BaseETLParamsBuilder
{
	public function addTerms(array $terms)
	{
		$this->params['terms'] = $terms;
	}
	
	public function addBool(BoolBuilder $bool)
	{
		$this->params['bool'] = $bool->getParams();		
	}
}

class BoolBuilder extends BaseETLParamsBuilder
{
	public function addMust(array $must)
	{
		$this->params['must'][] = $must;		
	}
	
	public function addMustNot(array $mustNot)
	{
		$this->params['must_not'][] = $mustNot;
	}
	
	public function addShould(array $should)
	{
		$this->params['should'][] = $should;
	}
}
