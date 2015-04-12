<?php

namespace Glad\Driver\Adapters\Database;

class Adapter {

	protected $pdo;
	protected $constants;
	
	protected function checkPdoDriver($name = false, $exception = false)
	{
		if(! $name) {
			$name = $this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);	
		}
		
		if(array_search($name, $this->pdo->getAvailableDrivers()) === false) {
			if($exception) {
				throw new PDOException ("PDO does not support any driver.");
			}

			return false;
		}

		return true;
	}

}