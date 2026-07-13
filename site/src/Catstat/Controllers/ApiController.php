<?php
namespace Catstat\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Catstat\Config;

class ApiController {
	public function hello(Request $req, Response $resp, $args) {
		$resp->getBody()->write('Hello World! The data is stored in ' . Config::$data_path);
		return $resp;
	}
}
