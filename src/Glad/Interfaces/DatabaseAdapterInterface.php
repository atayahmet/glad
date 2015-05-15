<?php

namespace Glad\Interfaces;

interface DatabaseAdapterInterface {
	public function newUser(array $credentials);
	public function getIdentity($identity);
	public function getIdentityWithId($userId);
}