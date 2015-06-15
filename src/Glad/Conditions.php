<?php

namespace Glad;

use Glad\Interfaces\ConditionsInterface;
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
class Conditions implements ConditionsInterface 
{
	/**
     * Collect results
     *
     * @var array
     */
	protected $checkResult = [];
	
	/**
     * Result the conditions
     *
     * @var bool
     */
	protected $check = true;

	/**
     * All conditions
     *
     * @var array
     */
	protected $conditions = [];

	/**
     * Apply conditions
     *
     * @param array $user user parameters
     * @param array $cond conditions
     * @param object $eventDispatcher after conditions behavior manage
     *
     * @return bool
     */ 
	public function apply(array $user, $cond = array(), Dispatcher $eventDispatcher)
	{
		$this->check = true;
		$this->add($cond);
		
		foreach($this->conditions as $field => $value){

			$this->checkResult[$field] = isset($user[$field]) && $value == $user[$field];

			if(!isset($user[$field]) || $value != $user[$field]){
				if($eventDispatcher->has($field)) {
					$eventDispatcher->run($field);	
				}
				
				$this->check = false;
			}
		}
		return $this->check;
	}

	/**
     * Append condition
     *
     * @param array $cond
     *
     * @return void
     */ 
	public function add(array $cond)
	{
		$this->conditions = array_merge($this->conditions, $cond);
	}

	/**
     * Process result
     *
     * @return bool
     */ 
	public function status()
	{
		return $this->checkResult;
	}
}