<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use Sycms\Bundle\ColumnBrowserBundle\Example\app\AppKernel;

require(__DIR__ . '/../../vendor/autoload.php');

Debug::enable();

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
