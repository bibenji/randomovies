<?php

namespace Randomovies\Twig\Extension;

use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PathExtension extends RoutingExtension
{
    protected $generator;
    protected $domain;

    public function __construct(UrlGeneratorInterface $generator, $domain)
    {
        parent::__construct($generator);
        $this->generator = $generator;
        $this->domain = $domain;
    }

    public function getPath($name, $parameters = array(), $relative = false)
    {
        return $this->domain . $this->generator->generate($name, $parameters, $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH);
    }
}
