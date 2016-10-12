<?php

$loader = require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../app/AppKernel.php";

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader([$loader, 'loadClass']);

use Symfony\Component\HttpFoundation\Request;

date_default_timezone_set('Europe/Berlin');

$request = Request::createFromGlobals();
$kernel = AppKernel::createFromEnvironment();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
