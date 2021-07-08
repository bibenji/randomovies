<?php

namespace Randomovies\Twig\Extension;

use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GenerateAExtension extends AbstractExtension
{
    protected $routing;

    public function __construct(RoutingExtension $routing)
    {
        $this->routing = $routing;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('a_generate', [$this, 'generateA']),
            new TwigFunction('a_generate_from_route', [$this, 'generateAWithPath']),
        ];
    }

    public function generateA($url, $insider, array $parameters = [])
    {
        return $this->generateATag($url, $insider, $parameters);
    }

    public function generateAWithPath($routeName, $insider, array $parameters = [])
    {
        return $this->generateATag(
            $this->routing->getPath($routeName, $parameters),
            $insider,
            $parameters
        );
    }

    private function generateATag($href, $insider, $parameters)
    {
        if (isset($parameters['tag_id']) && is_array($parameters['tag_id'])) {
            $ids = implode(' ', $parameters['tag_id']);
        }

        if (isset($parameters['tag_class']) && is_array($parameters['tag_class'])) {
            $classes = implode(' ', $parameters['tag_class']);
        }

        $a = '<a href="';
        $a .= $href;
        $a .= '" target="_top"';

        if (isset($ids)) {
            $a .= ' id="' . $ids . '"';
        }

        if (isset($classes)) {
            $a .= ' class="' . $classes . '"';
        }

        $a .= '>';
        $a .= $insider;
        $a .= '</a';

        return $a;
    }
}