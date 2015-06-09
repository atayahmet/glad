<?php

namespace Glad\Mocks;

use Glad\Interfaces\CookerInterface;

class CookerMock implements CookerInterface
{
	public function set($name = false, $value = false, $lifeTime = '', $path = '/', $domain = '.', $secure = false, $httpOnly = false)
	{
		if($name && $value) {
			// return setcookie($name, $value, $lifeTime, $path, $domain, $secure, $httpOnly);
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
			// return setcookie($name, '', strtotime( '-365 days' ), '/', ".".$_SERVER['HTTP_HOST'], false, true);
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
		// return isset($_COOKIE[$name]) ? true : false;
	}
}