<?php

namespace Glad;

use InvalidArgumentException;

class DatabaseService {

	public static function get($driver)
	{
		if(get_class($driver) == 'PDO') {
			return new Glad\Model\Adapters\PDO\User($driver);
		}

		if(static::_checkInterface(class_implements($driver))) {
			return new Glad\Model\Adapters\Model\User($driver);
		}

		throw new InvalidArgumentException('Driver error');
	}

}