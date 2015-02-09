<?php

namespace Glad\Driver\Repository\NativeSession;

use Glad\Driver\Repository\RepositoryInterface;

class Session implements RepositoryInterface {
	
	public function __construct()
	{
		static::sessionStart();
	}

	public static function set($key = false, $data = false)
	{
		if(! $key || ! $data) return false;

		if($_SESSION[$key] = $data) {
			return true;
		}
		return false;
	}

	public static function get($key = false)
	{
		if(! $key || !isset($_SESSION[$key])) return false;

		return $_SESSION[$key];
	}

	public static function delete($key = false)
	{
		if(! $key) return false;

		if(isset($_SESSION[$key])) unset($_SESSION[$key]);

		return true;
	}

	protected static function sessionStart()
	{
		if(! static::checkSessionStarted()) {
			session_start();
		}
	}

	protected static function checkSessionStarted()
	{
		if (version_compare(phpversion(), '5.4.0', '>=')) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        }else{
            return session_id() === '' ? FALSE : TRUE;
        }
	}
}