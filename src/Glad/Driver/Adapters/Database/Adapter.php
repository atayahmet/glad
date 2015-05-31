<?php

namespace Glad\Driver\Adapters\Database;

/**
 * Database Adapter
 *
 * @author Ahmet ATAY
 * @category Adapter
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class Adapter {

	/**
     * PDO instance
     *
     * @var object
     */
	protected $pdo;

	/**
     * Glad\Constants class instance
     *
     * @var object
     */
	protected $constants;
	
	/**
     * Check the PDO driver
     *
     * @param string $name
     * @param bool $exception
     *
     * @return bool
     */ 
	protected function checkPdoDriver($name = false, $exception = false)
	{
		if(! $name) {
			$name = $this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);	
		}
		
		if(array_search($name, $this->pdo->getAvailableDrivers()) === false) {
			if($exception) {
				throw new PDOException ("PDO does not support any driver.");
			}
			return false;
		}
		return true;
	}
}