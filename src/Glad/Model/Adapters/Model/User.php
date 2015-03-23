<?php

namespace Glad\Model\Adapters\Model;

use Glad\Model\GladModelInterface;

class User {

	protected $model;

	public function __construct(GladModelInterface $model)
	{
		$this->model = $model;
	}

}