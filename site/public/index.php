<?php
/**
 * "May you heal so hard you do your hobbies again."
 *  -- https://www.threads.com/@withloveheba/post/DZ9ewrRiL1k
 **
 * if found, return to sleepycrow
 */

define('APP_ROOT', __DIR__ . '/..');

use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Catstat\Config;

require APP_ROOT . '/vendor/autoload.php';

// Load .env values and sanity-check
try {
	Dotenv\Dotenv::createImmutable(APP_ROOT)->load();
} catch (Exception $e) {
	die('Failed to load .env file - is the app set up correctly?<br>' . $e);
}

if (empty(Config::get_base_data_path())) die('Base data path does not exist!');

// Set up app
$app = AppFactory::create();
require APP_ROOT . '/src/routes.php';

$twig = Twig::create(APP_ROOT . '/templates', [ 'cache' => Config::get_template_cache_path() ]);
$app->add(TwigMiddleware::create($app, $twig));

$app->run();
