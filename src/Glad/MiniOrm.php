<?php

namespace Glad;

use Glad\OrmInterface;

class MiniOrm implements OrmInterface {

	protected static $connection;

	public function __construct(array $connection)
	{
		static::$connection = $connection;
	}

	public static function save()
	{

	}

	public static function find()
	{

	}
}