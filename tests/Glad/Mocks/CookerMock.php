<?php

namespace Glad\Mocks;

use Glad\Interfaces\CookerInterface;
use Glad\Grants\RepositoryHandler;

class CookerMock implements CookerInterface
{
	protected $repositoryHandler;

	public function __construct()
	{
		$this->repositoryHandler = new RepositoryHandler;
	}

	public function set($name = false, $value = false, $lifeTime = '', $path = '/', $domain = '.', $secure = false, $httpOnly = false)
	{
		if($name && $value) {
			return $this->repositoryHandler->save('cookie', [$name => 
					[
						'name' 	  => $name,
						'value'	  => $value,
						'lifeTime'=> $lifeTime,
						'path'	  => $path,
						'domain'  => $domain,
						'secure'  => $secure,
						'httpOnly'=> $httpOnly
					]
				]);
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
		return $this->repositoryHandler->get('cookie', $name);
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
			return $this->repositoryHandler->remove('cookie', $name);
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
		return $this->repositoryHandler->get('cookie', $name);
	}
}