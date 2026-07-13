<?php
/**
 * This file is a step of the setup sequence in /public/index.php
 */

use Slim\Routing\RouteCollectorProxy;
use Catstat\Controllers\ApiController;
use Catstat\Controllers\HomeController;

// -- Api routes --------------------------------------------------------------
$app->group('/api/v1', function (RouteCollectorProxy $group) {
	$group->get('/hello', [ApiController::class, 'hello']);
});

// -- Home routes -------------------------------------------------------------
$app->group('', function (RouteCollectorProxy $group) {
	$group->get('/', [HomeController::class, 'index']);
});
