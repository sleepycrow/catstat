<?php
namespace Catstat\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Catstat\Config;
use Catstat\Utils\DataUtils;

class ApiController {
	public function get_hashes(Request $_req, Response $resp, array $_args): Response {
		$hashes = [];
		$file_paths = glob(Config::get_user_data_path() . '/*.txt');

		foreach ($file_paths as $file_path) {
			$file_name = basename($file_path);
			$file_hash = md5_file($file_path);
			if ($file_hash !== false) $hashes[$file_name] = $file_hash;
		}
		
		$resp->getBody()->write(json_encode($hashes, JSON_FORCE_OBJECT));
		return $resp->withStatus(200)
			->withHeader('Content-Type', 'application/json');
	}
	
	public function get_user_file(Request $_req, Response $resp, array $args): Response {
		$filename = $args['name'];
		if (!DataUtils::is_valid_user_file_name($filename)) {
			return $resp->withStatus(400);
		}

		$file_path = DataUtils::get_path_for_user($filename, true);
		if (!file_exists($file_path)) {
			return $resp->withStatus(404);
		}

		$resp->getBody()->write(file_get_contents($file_path));
		return $resp->withStatus(200)
			->withHeader('Content-Type', 'text/plain');
	}
	
	public function put_user_file(Request $req, Response $resp, array $args): Response {
		// TODO: Add signature verification
		$filename = $args['name'];
		$username = str_replace('.txt', '', $filename);
		if (!DataUtils::is_valid_user_file_name($filename)) {
			return $resp->withStatus(400);
		}
		$cats = $req->getBody();

		$file_path = DataUtils::get_path_for_user($filename, true);
		$write_result = file_put_contents($file_path, $cats);
		$stats_result = $this->update_stats_for_user($username, $cats);

		return $resp->withStatus(($write_result !== false && $stats_result !== false) ? 200 : 500);
	}

	private function update_stats_for_user(string $username, string $cats): int|false {
		$stats = DataUtils::get_global_stats();
		if ($stats === false) {
			return false;
		}

		$stats[$username] = [
			'total_cats' => substr_count($cats, "\n") + 1,
		];

		return DataUtils::save_global_stats($stats);
	}
}
