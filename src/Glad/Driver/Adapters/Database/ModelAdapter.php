<?php

namespace Glad\Driver\Adapters\Database;

use Glad\Interfaces\DatabaseAdapterInterface;
use Glad\Driver\Adapters\Database\Adapter;

/**
 * User model adapter
 *
 * @author Ahmet ATAY
 * @category ModelAdapter
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class ModelAdapter extends Adapter {

	protected $model;

	public function __construct(DatabaseAdapterInterface $model)
	{
		$this->model = $model;
	}

	/**
     * Data update (indirect call)
     *
     * @param array $where
     * @param array $data
     *
     * @return bool
     */
	public function update(array $where, array $data)
	{
		return $this->model->gladUpdate(reset($where), $data);
	}

	/**
     * Data insert (indirect call)
     *
     * @param array $credentials
     *
     * @return bool
     */ 
	public function insert(array $credentials)
	{
		return $this->model->gladInsert($credentials);
	}

	public function __call($method, $params)
	{
		return $this->model->$method(reset($params));
	}

}