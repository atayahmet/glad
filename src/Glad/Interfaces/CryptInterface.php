<?php

namespace Glad\Interfaces;

interface CryptInterface {
	public static function encrypt($string);
	public static function decrypt($hash);
}