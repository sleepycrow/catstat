<?php
namespace Catstat;

class Config {
	public static string $base_data_path = __DIR__ . '/../../data';
	public static string $user_data_dir = '/users';
	public static string $stats_file_path = '/stats.json';

	public static function get_stats_file_path(): string {
		return Config::$base_data_path . Config::$stats_file_path;
	}

	public static function get_user_data_path(): string {
		return Config::$base_data_path . Config::$user_data_dir;
	}
}
