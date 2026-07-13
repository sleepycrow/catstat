<?php
/**
 * "May you heal so hard you do your hobbies again."
 *  -- https://www.threads.com/@withloveheba/post/DZ9ewrRiL1k
 **
 * if found, return to sleepycrow
 */

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

require __DIR__ . '/../src/routes.php';

$app->run();
