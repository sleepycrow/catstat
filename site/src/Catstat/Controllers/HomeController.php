<?php
namespace Catstat\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController {
	public function index(Request $_req, Response $resp, array $_args): Response {
		$resp->getBody()->write('Home controller says hello!');
		return $resp;
	}
}
