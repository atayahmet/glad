<?php

namespace Glad\Interfaces;

use Glad\Event\Dispatcher;

interface ConditionsInterface
{
	public function apply(array $user, $cond = array(), Dispatcher $dispatcher);

}