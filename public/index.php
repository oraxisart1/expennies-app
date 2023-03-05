<?php

declare( strict_types = 1 );

use DI\Container;
use Slim\App;

/** @var Container $container */
$container = require __DIR__ . '/../bootstrap.php';

$container->get( App::class )->run();
