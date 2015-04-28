<?php

namespace Glad\Interfaces;

interface HashInterface {
	public function verify($password = null, $hash = null);
	public function make($password, $algo = PASSWORD_BCRYPT, array $options = array());
}