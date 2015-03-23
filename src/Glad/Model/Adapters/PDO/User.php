.<?php

namespace Glad\Model\Adapters\Model;

use PDO;

class User {

	protected $model;

	public function __construct(PDO $model)
	{
		$this->model = $model;
	}

}