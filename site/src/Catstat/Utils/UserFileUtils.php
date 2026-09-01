<?php
namespace Catstat\Utils;

use Catstat\Config;

class UserFileUtils {
	private static string $username_pattern = "[0-9A-Za-z_\-\.]+";
	
	public static function get_path_for_user(string $username, bool $omit_ext = false): string {
		return Config::get_user_data_path() . '/' . $username . ($omit_ext ? '' : '.txt');
	}

	public static function is_valid_user_name(string $username): bool {
		return preg_match("/^".UserFileUtils::$username_pattern."$/", $username) !== false;
	}

	public static function is_valid_user_file_name(string $filename): bool {
		return preg_match("/^".UserFileUtils::$username_pattern."\.txt$/", $filename) !== false;
	}
}
