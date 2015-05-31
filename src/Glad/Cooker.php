<?php

namespace Glad;

use Glad\Interfaces\CookerInterface;

/**
 * Cookie setter class
 *
 * @author Ahmet ATAY
 * @category Cooker
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class Cooker implements CookerInterface {
	
	/**
     * Creates new cookie
     *
     * @param $name string
     * @param $value mixed
     * @param $lifetime integer
     * @param $path string
     * @param $domain string
     * @param $secure boolean
     * @param $httpOnly boolean
     *
     * @return boolean
     */
	public function set($name = false, $value = false, $lifeTime = '', $path = '/', $domain = '.', $secure = false, $httpOnly = false)
	{
		if($name && $value) {
			return setcookie($name, $value, $lifeTime, $path, $domain, $secure, $httpOnly);
		}
		return false;
	}

	/**
     * Get the value if exists
     *
     * @param $name string
     *
     * @return string|null
     */
	public function get($name)
	{
		return $this->has($name) ? $_COOKIE[$name] : null;
	}

	/**
     * Delete cookie
     *
     * @param $name string
     *
     * @return boolean
     */
	public function remove($name)
	{
		if($this->has($name)) {
			return setcookie($name, '', strtotime( '-365 days' ), '/', ".".$_SERVER['HTTP_HOST'], false, true);
		}
		return false;
	}

	/**
     * Check cookie exists
     *
     * @param $name string
     *
     * @return boolean
     */
	public function has($name)
	{
		return isset($_COOKIE[$name]) ? true : false;
	}
}