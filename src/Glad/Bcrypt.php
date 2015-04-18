<?php

namespace Glad;

class Bcrypt
{

	protected $cost = 8;
	protected $algorithm = PASSWORD_BCRYPT;

	/**
     * Verifying password
     *
     * @param string $password
     * @param string $hash string
     *
     * @return bool
     */ 
	public function verify($password = null, $hash = null)
	{
		return password_verify($password, $hash);
	}

	public function hash($password, $algo = PASSWORD_BCRYPT, array $options = array())
	{
		$options = static::checkOptions($options);

		return password_hash($password, $algo, $options);
	}

	protected function checkOptions(array $options)
	{
		if(count($options) < 1) {
			return ['cost' => $this->cost];
		}

		return $options;
	}
}