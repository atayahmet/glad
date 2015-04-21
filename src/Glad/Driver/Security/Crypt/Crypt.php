<?php

namespace Glad\Driver\Security\Crypt;

use Glad\Interfaces\CryptInterface;

class Crypt implements CryptInterface {

	protected static $secret = 'Thisismysecretkey';

	public static function encrypt($string)
	{
		$data = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, static::$secret, $string, MCRYPT_MODE_ECB, '');
        return base64_encode($data);
	}

	public static function decrypt($hash)
	{
    	return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, static::$secret, base64_decode($hash), MCRYPT_MODE_ECB, '');
	}


}