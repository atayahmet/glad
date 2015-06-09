<?php

namespace Glad\Grants;

use Glad\Interfaces\DatabaseServiceInterface;

class DatabaseService implements DatabaseServiceInterface
{
	public function get($driver)
	{
		return $driver;
	}
}