<?php

namespace Glad\Interfaces;

/**
 * User password hashing class interface
 *
 * @author Ahmet ATAY
 * @category HashInterface
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
interface HashInterface {

	/**
     * Verifying password
     *
     * @param string $password
     * @param string $hash string
     *
     * @return bool
     */ 
	public function verify($password = null, $hash = null);

	/**
     * Hashing password
     *
     * @param string $password
     * @param integer $cost
     * @param integer $algo
     *
     * @return string
     */ 
	public function make($password, $cost, $algo = PASSWORD_BCRYPT);
}