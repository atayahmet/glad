<?php

namespace Glad;

/**
 * Glad contant parameters
 *
 * @author Ahmet ATAY
 * @category Constants
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class Constants
{
	/**
     * Identity fields
     *
     * @var array
     */
	protected static $authFields = [
		'identity' => 'email', 
		'password' => 'password',
	];

	/**
     * Cookie domain
     *
     * @var string
     */
	protected static $cookieDomain  = '';

	/**
     * Hash cost
     *
     * @var int
     */
	protected static $cost = 5;

	/**
     * Crypt secret key
     *
     * @var string
     */
	protected static $secret = '|3|#__()=?';

	/**
     * Remember process parameters
     *
     * @var array
     */
	protected static $remember = [
		'enabled' 	=> false, 
		'cookieName'=> '_glad_auth', 
		'lifetime' 	=> 31536000, 
		'field' 	=> 'remember_token'
	];

	/**
     * Session repository parameters
     *
     * @var array
     */
	protected static $repository = [
		'driver'  => 'session',
		'options' => [
			'session' => [
				'path'   => '/',
				'type'   => 'serialize',
				'name' 	 => 'SESSIONID',
				'prefix' => 'ses_',
				'crypt'	 => false,
				'timeout'=> 3600
			],
			'memcache' 	=> [
				'host'	  => '127.0.0.1',
				'port'	  => 11211,
				'timeout' => 3600,
				'prefix'  => 'ses_',
				'crypt'	  => false,
				'name'	=> 'SESSIONID'

			],
			'memcached' => [
				'host'	  => '127.0.0.1',
				'port'	  => 11211,
				'timeout' => 3600,
				'prefix'  => 'ses_',
				'crypt'	  => false,
				'name'	=> 'SESSIONID',
			]
		]
	];

	/**
     * User table unique field
     *
     * @var string
     */
	protected static $id = 'id';

	/**
     * User table name
     *
     * @var string
     */
	protected static $table = 'users';

	/**
     * Conditions that apply to user data
     *
     * @var array
     */
	protected static $conditions = [];
 
	public function __get($attr)
	{
		return isset(static::$$attr) ? static::$$attr : null;
	}
}