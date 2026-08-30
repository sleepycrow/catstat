<?php
namespace Catstat\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class HomeController {
	public function index(Request $req, Response $resp, array $_args): Response {
		$view = Twig::fromRequest($req);
		return $view->render($resp, 'home.html.twig');
	}
	
	public function lookup(Request $req, Response $resp, array $_args): Response {
		$view = Twig::fromRequest($req);
		$username = $req->getQueryParams()['user'] ?? null;
		
		return $view->render($resp, 'test.html.twig', [ 'msg' => ('Got lookup request for ' . $username . '!')]);
	}
}
