<?php

namespace Glad;

class Constants {

	protected static $authFields;
	protected static $table = 'users';
	protected static $conditions = [];

	public function __get($attr)
	{
		return isset(static::$$attr) ? static::$$attr : null;
	}
}