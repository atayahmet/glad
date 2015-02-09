<?php

namespace Glad\Model;

interface GladModelInterface {
	public function newUser(array $credentials);
	public function getIdentity($identity);
	public function getIdentityWithId($userId);
}