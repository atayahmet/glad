<?php

namespace Glad\Services;

use InvalidArgumentException;
use Glad\Service;
use Glad\Interfaces\ServiceInterface;

/**
 * Database Service
 *
 * @author Ahmet ATAY
 * @category DatabaseService
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class DatabaseService extends Service implements ServiceInterface {

	/**
     * Get the active service
     *
     * @param object $driver
     *
     * @return bool
     */ 
	public function get($driver)
	{
		if(get_class($driver) == 'PDO') {
			return new \Glad\Driver\Adapters\Database\PDOAdapter($driver);
		}

		else if(isset(class_implements($driver)['Glad\Interfaces\DatabaseAdapterInterface'])) {
			return new \Glad\Driver\Adapters\Database\ModelAdapter($driver);
		}

		throw new InvalidArgumentException('Glad Database Adapter error');
	}

}