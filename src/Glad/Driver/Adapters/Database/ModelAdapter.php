<?php

namespace Glad\Driver\Adapters\Database;

use Glad\Interfaces\DatabaseAdapterInterface;
use Glad\Driver\Adapters\Database\Adapter;

class ModelAdapter extends Adapter {

	protected $model;

	public function __construct(DatabaseAdapterInterface $model)
	{
		$this->model = $model;
	}

	public function __call($method, $params)
	{
		return $this->model->$method(reset($params));
	}

}