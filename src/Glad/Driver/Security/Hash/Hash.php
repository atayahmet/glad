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
     * @param array $cost
     * @param integer $algo
     *
     * @return string
     */ 
	public function make($password, $cost, $algo = PASSWORD_BCRYPT)
	{
		return password_hash($password, $algo, ['cost' => $cost]);
	}
}