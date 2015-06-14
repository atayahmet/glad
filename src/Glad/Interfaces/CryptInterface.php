<?php

namespace Glad\Interfaces;

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
interface CryptInterface {

	/**
     * Data encrypt
     *
     * @param string $string
     *
     * @return string
     */ 
	public static function encrypt($string, $secret);

	/**
     * Data Decrypt
     *
     * @param string $string
     *
     * @return mixed
     */ 
	public static function decrypt($hash, $secret);
}