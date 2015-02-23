<?php

namespace Glad;

class Conditions implements ConditionsInterface {
	
	protected $checkResult = [];
	protected $check = true;
	protected $conditions = [];

	public function apply(array $user, array $cond = [])
	{
		$this->add($cond);

		foreach($this->conditions as $field => $value){

			$this->checkResult[$field] = isset($user[$field]) && $value == $user[$field];

			if(!isset($user[$field]) || $value != $user[$field]){
				$this->check = false;
			}
		}
		return $this->check;
	}

	public function add(array $conditions)
	{
		$this->conditions = array_merge($this->conditions, $conditions);
	}

	public function status()
	{
		return $this->checkResult;
	}
}