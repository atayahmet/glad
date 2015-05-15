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
	protected static $id = 'id';
	protected static $table = 'users';
	protected static $conditions = [];
 
	public function __get($attr)
	{
		return isset(static::$$attr) ? static::$$attr : null;
	}
}