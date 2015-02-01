<?php

namespace Glad\Driver\Repository\NativeSession;

use Glad\Driver\Repository\RepositoryInterface;

class Session implements RepositoryInterface {
	
	public static function set()
	{

	}

	public static function get($key)
	{
		echo $key;
	}
}