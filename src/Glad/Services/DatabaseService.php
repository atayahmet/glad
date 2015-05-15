<?php

namespace Glad\Services;

use InvalidArgumentException;
use Glad\Service;
use Glad\Interfaces\ServiceInterface;

class DatabaseService extends Service implements ServiceInterface {

	public function get($driver)
	{
		if(get_class($driver) == 'PDO') {
			return new \Glad\Driver\Adapters\Database\PDOAdapter($driver);
		}

		else if(isset(class_implements($driver)['Glad\Interfaces\DatabaseAdapterInterface'])) {
			return new \Glad\Driver\Adapters\Database\ModelAdapter($driver);
		}

		throw new InvalidArgumentException('Glad Database Adapter error');
	}

}