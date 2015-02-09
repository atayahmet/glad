<?php

namespace Glad;

class Constants {

	protected static $authFields;
	protected static $table = 'users';

	public function __get($attr)
	{
		return isset(static::$$attr) ? static::$$attr : null;
	}
}