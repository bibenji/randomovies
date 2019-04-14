<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use Randomovies\Kernel;

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';

include_once __DIR__.'/../var/bootstrap.php.cache';

$env = $_SERVER['KERNEL_ENV'] ?: getenv('SYMFONY_ENV') ?: 'dev';
$debug = 'dev' === $env;

if ($debug) {
    Debug::enable();
}

$kernel = new Kernel($env, $debug);

$kernel->loadClassCache();
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
