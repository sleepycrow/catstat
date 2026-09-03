<?php
namespace Catstat\Utils;

use Catstat\Config;

class DataUtils {
	private static string $username_pattern = "[0-9A-Za-z_\-\.]+";
	
	public static function get_path_for_user(string $username, bool $omit_ext = false): string {
		return Config::get_user_data_path() . '/' . $username . ($omit_ext ? '' : '.txt');
	}

	public static function is_valid_user_name(string $username): bool {
		return preg_match("/^".DataUtils::$username_pattern."$/", $username) !== false;
	}

	public static function is_valid_user_file_name(string $filename): bool {
		return preg_match("/^".DataUtils::$username_pattern."\.txt$/", $filename) !== false;
	}

	public static function get_global_stats(): array|false {
		$file_path = Config::get_stats_file_path();
		$raw_data = '{}';
		
		if (file_exists($file_path)) {
			$raw_data = file_get_contents($file_path);
			if ($raw_data === false) {
				return false;
			}
		}
		
		$data = json_decode($raw_data, true);
		if ($data === null) {
			return false;
		}

		return $data;
	}

	public static function save_global_stats(array $data): int|false {
		$encoded_data = json_encode($data);
		if ($encoded_data === false) {
			return false;
		}
		return file_put_contents(Config::get_stats_file_path(), $encoded_data); // not multi writer safe but yolo, should be okay for this case
	}
}
