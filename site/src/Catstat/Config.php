<?php
namespace Catstat;

class Config {
	public static string $user_data_dir = '/users';
	public static string $stats_file_path = '/stats.json';

	private static function resolve_path(string $raw_path): string|false {
		$path = $raw_path[0] === '.' ? APP_ROOT . '/' . $raw_path : $raw_path;
		return realpath($path);
	}

	public static function get_base_data_path(): string {
		return Config::resolve_path($_ENV['DATA_PATH']);
	}

	public static function get_stats_file_path(): string {
		return Config::get_base_data_path() . Config::$stats_file_path;
	}

	public static function get_user_data_path(): string {
		return Config::get_base_data_path() . Config::$user_data_dir;
	}

	public static function get_template_cache_path(): string|false {
		if (empty($_ENV['TEMPLATE_CACHE_PATH'])) return false;
		return Config::resolve_path($_ENV['TEMPLATE_CACHE_PATH']);
	}
}
