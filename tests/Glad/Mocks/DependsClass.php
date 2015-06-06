<?php

namespace Glad\Mocks;

use Glad\Interfaces\CookerInterface;

class DependsClass {
	public function foo(CookerInterface $cooker)
	{
		return $cooker;
	}
}