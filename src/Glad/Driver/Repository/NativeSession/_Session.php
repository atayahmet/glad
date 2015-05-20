<?php

namespace Glad\Driver\Repository\NativeSession;

use Glad\Driver\Repository\RepositoryInterface;

/**
 * Session class
 *
 * @author Ahmet ATAY
 * @category Session
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class Session implements RepositoryInterface, \SessionHandlerInterface{
	
	/**
     * Class constructor
     *
     * @return void
     */
	public function __construct()
	{
		static::sessionStart();
	}

	/**
     * Sets data to session
     *
     * @param string $key
     * @param mixed $data
     * @return bool
     */
	public static function set($key = false, $data = false)
	{
		if(! $key || ! $data) return false;

		if($_SESSION[$key] = $data) {
			return true;
		}
		return false;
	}

	/**
     * Gets data from session
     *
     * @param string $key
     * @return bool
     */
	public static function get($key = false)
	{
		if(! $key || !isset($_SESSION[$key])) return false;

		return $_SESSION[$key];
	}

	/**
     * Delete data from session
     *
     * @param string $key
     * @return bool
     */
	public static function delete($key = false)
	{
		if(! $key) return false;

		unset($_SESSION[$key]);

		return true;
	}

	/**
     * Check and starts session process
     *
     * @return bool
     */
	protected static function sessionStart()
	{
		if(! static::checkSessionStarted()) {
			session_start();
		}
	}

	/**
     * Checks if the session has been started
     *
     * @return bool
     */
	protected static function checkSessionStarted()
	{
		if (version_compare(phpversion(), '5.4.0', '>=')) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        }else{
            return session_id() === '' ? FALSE : TRUE;
        }
	}
}