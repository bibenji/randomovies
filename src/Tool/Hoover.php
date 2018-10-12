<?php

namespace Randomovies\Tool;

use DOMDocument;
use DOMXPath;

class Hoover
{
	/**
	 * 
	 * @var array
	 */
	protected $data;
	
	/**	 
	 * @param string $url
	 * @return array $data
	 */
	public function aspireWikipedia(string $url): array
	{		
		$dom = new DOMdocument('1.0', 'utf-8');
		@$dom->loadHTMLFile($url);
		
		$xpath = new DOMXPath($dom);
		// correspond à la première infobox en haut à droite
		$els = $xpath->query("//div[contains(@class,'infobox_v3 large')]");		
		
// 		$els = $xpath->query("//div[@name='node']");
// 		$els = $xpath->query("//cite");		
		//To get it immediately:
		//$els = $xpath->query("//div[@name='node']/following-sibling::*[1]");		
		//To get it when you already have a <div name="node">:
		//$nextelement = $xpath->query("following-sibling::*[1]", $currentdiv);
				
		$basicHandling = function($keys, $node, &$data) {
			foreach ($keys as $wikipediaText => $randomoviesText)
				if ($wikipediaText === $node->nodeValue) {
					dump($wikipediaText);
					$targets = $node->nextSibling->nextSibling->getElementsByTagName('*');
					if ($targets[0]) {
						$data[$randomoviesText] = $targets[0]->nodeValue;
					} else {
						$data[$randomoviesText] = $node->nextSibling->nextSibling->childNodes[0]->nodeValue; // cas pour Durée
					}
				}
		};
		
		$categories = [
				'Réalisation' => 'director',
				'Acteurs principaux' => 'actors',				
				'Genre' => 'genre',
				'Durée' => 'duration',
				'Sortie' => 'year',
		];
		
		$data = [];
				
		$els = $els[0]->getElementsByTagName('th');
				
		foreach ($els as $node) {
			if (array_key_exists($node->nodeValue, $categories)) {				
				if ('Acteurs principaux' === $node->nodeValue) {					
					$targets = $node->nextSibling->nextSibling->getElementsByTagName('*');
					$data['actors'] = '';
					foreach ($targets as $target) {						
						if (preg_match('/^\w+ \S+$/', $target->nodeValue)) {
							$data['actors'] .= $target->nodeValue.', ';
						}
					}					
					$data['actors'] = rtrim($data['actors'], ', ');
				} else {
					$basicHandling($categories, $node, $data);
				}
			}			
		}
		
		$currentPSynopsis = $dom->getElementById('Synopsis')->parentNode->nextSibling->nextSibling;		
		$data['synopsis'] = '';
		while (isset($currentPSynopsis->tagName) && 'p' === $currentPSynopsis->tagName) {			
			$data['synopsis'] .= $currentPSynopsis->nodeValue.' ';
			$currentPSynopsis = $currentPSynopsis->nextSibling;
		}
		$data['synopsis'] = rtrim($data['synopsis']);
		
		$data['title'] = $dom->getElementById('firstHeading')->getElementsByTagName('i')[0]->nodeValue;
				
		preg_match('/(\d+)/', $data['duration'], $matches);
		$data['duration']= trim($matches[1]);
		
		return $data;
	}
	
	public function mapDataToMovie($data, &$movie)
	{
		foreach ($data as $key => $value) {
			$method = 'set'.ucfirst($key);
			if (method_exists($movie, $method)) {
				$movie->$method($value);				
			}
		}
	}	
}
