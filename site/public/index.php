<?php
/**
 * "May you heal so hard you do your hobbies again."
 *  -- https://www.threads.com/@withloveheba/post/DZ9ewrRiL1k
 **
 * if found, return to sleepycrow
 */

use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
require __DIR__ . '/../src/routes.php';

$twig = Twig::create(__DIR__ . '/../templates', [ 'cache' => false ]); // TODO: enable on prod, keep disabled on dev
$app->add(TwigMiddleware::create($app, $twig));

$app->run();
