<?php

namespace Glad\Driver\Security\Hash;

use Glad\Interfaces\HashInterface;

/**
 * User password hashing class
 *
 * @author Ahmet ATAY
 * @category Hash
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class Hash implements HashInterface
{
	protected $cost = 8;
	protected $algorithm = PASSWORD_BCRYPT;

	/**
     * Verifying password
     *
     * @param string $password
     * @param string $hash string
     *
     * @return bool
     */ 
	public function verify($password = null, $hash = null)
	{
		return password_verify($password, $hash);
	}

	/**
     * Hashing password
     *
     * @param string $password
     * @param integer $algo
     * @param array $option
     *
     * @return string
     */ 
	public function make($password, $algo = PASSWORD_BCRYPT, array $options = array())
	{
		$options = static::checkOptions($options);

		return password_hash($password, $algo, $options);
	}

	/**
     * Check hash option password
     *
     * @param array $options
     *
     * @return string
     */ 
	protected function checkOptions(array $options)
	{
		if(count($options) < 1) {
			return ['cost' => $this->cost];
		}

		return $options;
	}
}