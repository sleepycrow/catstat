<?php
namespace Catstat\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController {
	public function index(Request $req, Response $resp, $args) {
		$resp->getBody()->write('Home controller says hello!');
		return $resp;
	}
}
