<?php

namespace Randomovies\Twig\Extension;

use Doctrine\ORM\EntityManager;
use Twig\TwigFilter;

class RandomMoviesExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getGlobals()
    {
        return array (
            "fourRandoMovies" => $this->em->getRepository('Randomovies:Movie')->getRandomMovies(),
            "distinctCategories" => $this->em->getRepository('Randomovies:Movie')->getDistinctCategories(),
        );
    }

    public function getFilters()
    {
        return array(
            new TwigFilter('camelize', array($this, 'camelizeFilter')),
        );
    }

    public function getName()
    {
        return "RandomMoviesExtension";
    }

    public function camelizeFilter(String $value)
    {
        if(!is_string($value)) {
            return $value;
        }

//        $value = strtr($value, 'ÁÀÂÄÃÅÇÉÈÊËÍÏÎÌÑÓÒÔÖÕÚÙÛÜÝ', 'AAAAAACEEEEEIIIINOOOOOUUUUY');
//        $value = strtr($value, 'áàâäãåçéèêëíìîïñóòôöõúùûüýÿ', 'aaaaaaceeeeiiiinooooouuuuyy');

        $value = str_replace('é', 'e', $value);

        $chunks = explode(' ', $value);

        $lowered = array_map(function($s) { return strtolower($s); }, $chunks);

        return implode('-', $lowered);
    }
}
