<?php
namespace Catstat\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Catstat\Utils\UserFileUtils;

class HomeController {
	public function index(Request $req, Response $resp, array $_args): Response {
		$view = Twig::fromRequest($req);
		return $view->render($resp, 'home.html.twig');
	}
	
	public function lookup(Request $req, Response $resp, array $_args): Response {
		$view = Twig::fromRequest($req);
		$username = $req->getQueryParams()['user'] ?? null;
		
		$file_path = UserFileUtils::get_path_for_user($username);
		if (!UserFileUtils::is_valid_user_name($username) || !file_exists($file_path)) {
			$resp->withStatus(404);
			return $view->render($resp, 'test.html.twig', [ 'msg' => ('Not found!')]);
		}

		$file = fopen($file_path, "r");
		if (!$file) {
			$resp->withStatus(500);
			return $view->render($resp, 'test.html.twig', [ 'msg' => ('Read error!')]);
		}

		$all_cats = [];
		while (($cat = fgets($file)) !== false) {
			array_push($all_cats, $cat);
		}
		fclose($file);
		
		return $view->render($resp, 'lookup.html.twig', [
			'username' => $username,
			'cats' => $all_cats,
		]);
	}
}
