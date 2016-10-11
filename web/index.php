<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../app/AppKernel.php";

use Symfony\Component\HttpFoundation\Request;

date_default_timezone_set('Europe/Berlin');

$request = Request::createFromGlobals();
$kernel = new AppKernel('dev', true);
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
