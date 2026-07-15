<?php
namespace Catstat;

class Config {
	public static string $base_data_path = __DIR__ . '/../../data';
	public static string $user_data_dir = '/users';

	public static function get_user_data_path(): string {
		return Config::$base_data_path . Config::$user_data_dir;
	}
}
