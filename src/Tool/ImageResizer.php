<?php

namespace Randomovies\Tool;

class ImageResizer
{
	const SMALL_THUMBNAIL_WIDTH = 400;
	const SMALL_THUMBNAIL_HEIGHT = 600;
	const MEDIUM_THUMBNAIL_WIDTH = 600;
	const MEDIUM_THUMBNAIL_HEIGHT = 800;
	
	public function makeSmallAndMediumThumbnails($postersDirectory, $posterName)
	{
		$this->makeSmallThumbnail($postersDirectory, $posterName);
		$this->makeMediumThumbnail($postersDirectory, $posterName);
	}
	
	public function makeSmallThumbnail($postersDirectory, $posterName)
	{
		if (file_exists($postersDirectory.'/'.$posterName)) {
			$this->makeThumbnail($postersDirectory, $posterName, self::SMALL_THUMBNAIL_WIDTH, self::SMALL_THUMBNAIL_HEIGHT, 'small');
		}		
	}
	
	public function makeMediumThumbnail($postersDirectory, $posterName)
	{
		if (file_exists($postersDirectory.'/'.$posterName)) {
			$this->makeThumbnail($postersDirectory, $posterName, self::MEDIUM_THUMBNAIL_WIDTH, self::MEDIUM_THUMBNAIL_HEIGHT, 'medium');			
		}
	}
	
	private function makeThumbnail($postersDirectory, $posterName, $destWidth = 200, $destHeight = 300, $subfolder = 'small')
	{
		$source = imagecreatefromjpeg($postersDirectory.'/'.$posterName); // La photo est la source

		$destination = imagecreatetruecolor($destWidth, $destHeight); // On crée la miniature vide

		// infos utiles
		$largeur_source = imagesx($source);
		$hauteur_source = imagesy($source);

		$source_x = 0;
		$source_y = 0;

		$largeur_destination = imagesx($destination);
		$hauteur_destination = imagesy($destination);

		$destination_x = 0;
		$destination_y = 0;

		$rapport_w = $largeur_source / $largeur_destination;
		$rapport_h = $hauteur_source / $hauteur_destination;

		if ($rapport_w <= $rapport_h) {
			$proportion = $rapport_h / $rapport_w;
			$hauteur_source_proportionnelle = $hauteur_source / $proportion;
			$source_y = ($hauteur_source - $hauteur_source_proportionnelle) / 2;
			$hauteur_source = $hauteur_source_proportionnelle;
		} else {
			// $rapport_w > $rapport_h
			$proportion = $rapport_w / $rapport_h;
			$largeur_source_proportionnelle = $largeur_source / $proportion;
			$source_x = ($largeur_source - $largeur_source_proportionnelle) / 2;
			$largeur_source = $largeur_source_proportionnelle;
		}

		// ça à utiliser si image plus petite
		// imagecopymerge($destination, $source, $destination_x, $destination_y, 0, 0, $largeur_source, $hauteur_source, 60);

		// ça à utiliser si image plus grande
		imagecopyresampled($destination, $source, $destination_x, $destination_y, $source_x, $source_y, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);

		// imagejpeg($destination);
		
		// $newPosterName = str_replace('.jpgeg', '', $posterName);
		// $newPosterName = str_replace('.jpg', '', $posterName);		
		
		imagejpeg($destination, $postersDirectory.'/'.$subfolder.'/'.$posterName);		
	}
}