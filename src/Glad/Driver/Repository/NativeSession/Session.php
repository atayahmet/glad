<?php

namespace Glad\Driver\Repository\NativeSession;

use Glad\Driver\Repository\RepositoryInterface;

class Session implements RepositoryInterface {
	
	public static function set($key)
	{
		echo $key;
	}

	public static function get($key)
	{
		echo $key;
	}
}