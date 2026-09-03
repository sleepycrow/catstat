<?php
namespace Catstat\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Catstat\Utils\DataUtils;

class HomeController {
	public function index(Request $req, Response $resp, array $_args): Response {
		$view = Twig::fromRequest($req);
		return $view->render($resp, 'home.html.twig', [
			'leaderboard' => $this->get_leaderboard(),
		]);
	}
	
	public function lookup(Request $req, Response $resp, array $_args): Response {
		$view = Twig::fromRequest($req);
		$username = $req->getQueryParams()['user'] ?? null;
		
		$file_path = DataUtils::get_path_for_user($username);
		if (!DataUtils::is_valid_user_name($username) || !file_exists($file_path)) {
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

		$leaderboard_key = array_find_key($this->get_leaderboard(), fn($val): bool => ($val['user'] === $username));

		return $view->render($resp, 'lookup.html.twig', [
			'username' => $username,
			'cats' => $all_cats,
			'leaderboard_pos' => $leaderboard_key !== null ? ($leaderboard_key + 1) : null,
		]);
	}

	private function get_leaderboard(): array {
		$stats = DataUtils::get_global_stats();
		
		$cats_per_user = $stats;
		$cats_per_user = array_map(fn($val): int => $val['total_cats'], $cats_per_user);
		$cats_per_user = array_filter($cats_per_user);
		arsort($cats_per_user); // oh god what even is php why

		$leaderboard = [];
		foreach ($cats_per_user as $user => $cats) {
			array_push($leaderboard, ['user' => $user, 'totalCats' => $cats]);
		}
		return $leaderboard;
	}
}
