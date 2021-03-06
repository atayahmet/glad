<?php

namespace Glad\Event;

use Glad\Glad;

/**
 * Event dispatcher class
 *
 * @author Ahmet ATAY
 * @category Dispatcher
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class Dispatcher
{
	/**
      * All events
      *
      * @var array
      */
	protected $_events = [];

	/**
      * Glad container class
      *
      * @var object
      */
	protected $container;

	/**
      * Save the events
      *
      * @param string $name
      * @param Closure $event
      *
      * @return void
      */ 
	public function set($name, $event = false)
	{
		$this->_events[$name] = $event;
	}

	/**
      * Run the one event or all events
      *
      * @param string $name
      * @param mixed $params
      *
      * @return mixed
      */ 
	public function run($name = false)
	{
		if($name !== false && isset($this->_events[$name])){
			return $this->_events[$name]($this->container);
		}

		foreach($this->_events as $event){
                  $event($this->container);
            }
	}

	/**
      * Check the event existing
      *
      * @param string $name
      *
      * @return bool
      */ 
	public function has($name)
	{
		return isset($this->_events[$name]);
	}

	/**
      * Set the Glad container class
      *
      * @param object $instance
      *
      * @return void
      */ 
	public function setInstance(Glad $instance)
	{
		$this->container = $instance;
	}
}