<?php

namespace Glad\Interfaces;

use Glad\Event\Dispatcher;

/**
 * Apply conditions the user parameters
 *
 * @author Ahmet ATAY
 * @category Conditions
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
interface ConditionsInterface
{
	/**
     * Apply conditions
     *
     * @param array $user user parameters
     * @param array $cond conditions
     * @param object $eventDispatcher after conditions behavior manage
     *
     * @return bool
     */ 
	public function apply(array $user, $cond = array(), Dispatcher $dispatcher);

}