<?php

namespace Glad\Driver\Security\Crypt;

use Glad\Interfaces\CryptInterface;

/**
 * Encrypt / Decrypt class
 *
 * @author Ahmet ATAY
 * @category Crypt
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class Crypt implements CryptInterface {

	protected static $secret = '|3|#__()=?';

	/**
     * Data encrypt
     *
     * @param string $string
     *
     * @return string
     */ 
	public static function encrypt($string)
	{
		$data = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, static::$secret, $string, MCRYPT_MODE_ECB, '');
        return base64_encode($data);
	}

	/**
     * Data Decrypt
     *
     * @param string $string
     *
     * @return mixed
     */ 
	public static function decrypt($hash)
	{
    	return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, static::$secret, base64_decode($hash), MCRYPT_MODE_ECB, '');
	}
}