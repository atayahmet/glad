<?php

namespace Glad\Event;

use Glad\Glad;

class Dispatcher
{
	protected $_events = [];
	protected $container;

	public function set($name, $event = false)
	{
		$this->_events[$name] = $event;
	}

	public function run($name = false, $params = false)
	{
		if($name && isset($this->_events[$name])){
			return $this->_events[$name]($this->container);
		}

		foreach($this->_events as $event){
			$event($this->container);
		}
	}

	public function has($name)
	{
		return isset($this->_events[$name]);
	}

	public function setInstance(Glad $instance)
	{
		$this->container = $instance;
	}
}