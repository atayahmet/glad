<?php

namespace Glad;

interface GladModelInterface {
	public static function newUser(array $credentials);
	public static function getIdentity($identity);
	public static function getIdentityWithId($userId);
}