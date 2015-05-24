<?php

namespace Glad;

class Constants
{
	protected static $authFields;
	protected static $remember = [
		'cookieName' 		=> '_glad_auth', 
		'enabled' 	=> true, 
		'lifetime' 	=> 31536000, 
		'field' 	=> 'remember_token'
	];

	protected static $repository = [
		'driver'  => 'session',
		'options' => [
			'session' => [
				'path'   => '/',
				'type'   => 'serialize',
				'name' 	 => 'SESSIONID',
				'prefix' => 'ses_',
				'timeout'=> 1800
			],
			'memcache' 	=> [
				'host'	  => '127.0.0.1',
				'port'	  => 11211,
				'timeout' => 1800,
				'type'	  => 'serialize',
				'prefix'  => 'ses_'

			],
			'memcached' => [],
			'redis'		=> []
		]
			
	];
	protected static $repositoryDriver = 'session';

	protected static $id = 'id';
	protected static $table = 'users';
	protected static $conditions = [];
 
	public function __get($attr)
	{
		return isset(static::$$attr) ? static::$$attr : null;
	}
}