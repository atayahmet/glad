<?php

namespace Glad\Interfaces;

use SessionHandlerInterface;
use Glad\Interfaces\CryptInterface;

/**
 * SessionHandlerInterface extends to GladSessionHandlerInterface
 *
 * @author Ahmet ATAY
 * @category GladSessionHandlerInterface
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
interface GladSessionHandlerInterface extends SessionHandlerInterface {

	/**
     * Start the session process
     *
     * @param array $config
     * @param object $crypt
     *
     * @return void
     */ 
	public function openSession(array $config, CryptInterface $crypt);

}