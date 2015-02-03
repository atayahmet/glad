<?php

namespace Glad;

class Constants {

	protected static $authFields;

	public function __get($attr)
	{
		return isset(static::$$attr) ? static::$$attr : null;
	}
}