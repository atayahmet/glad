<?php

namespace Glad\Mocks;

use Glad\Interfaces\CookerInterface;

class DependenceClass {
	public function foo(CookerInterface $cooker)
	{
		return $cooker;
	}
}