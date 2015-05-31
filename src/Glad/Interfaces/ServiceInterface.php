<?php

namespace Glad\Interfaces;

/**
 * Service interface
 *
 * @author Ahmet ATAY
 * @category ServiceInterface
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
interface ServiceInterface {

	/**
	 * Verifying password
     *
     * @param mixed $driver
     *
     * @return mixed
     */ 
	public function get($driver);

}