<?php
namespace Catstat\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Catstat\Config;

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
		if (!$this->is_valid_user_file_name($filename)) {
			return $resp->withStatus(400);
		}

		$file_path = Config::get_user_data_path() . '/' . $filename;
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
		if (!$this->is_valid_user_file_name($filename)) {
			return $resp->withStatus(400);
		}

		$file_path = Config::get_user_data_path() . '/' . $filename;
		$write_result = file_put_contents($file_path, $req->getBody());

		return $resp->withStatus($write_result !== false ? 200 : 500);
	}

	private function is_valid_user_file_name(string $filename): bool {
		return preg_match("/^[0-9A-Za-z_\-\.]+\.txt$/", $filename) !== false;
	}
}
