<?php

namespace Glad;

use Glad\Driver\Repository\RepositoryInterface;
use Glad\Driver\Repository\Redis\RedisRepository;
use Glad\Test;
use Closure;

class Author {

	public function getIdentity(RepositoryInterface $session, RepositoryInterface $authh)
	{
		$session->get('hello');
	}

	public function getRepository(RepositoryInterface $repository)
	{
		return $repository;
	}

	public function login(array $user, $remember, RepositoryInterface $repository, RedisRepository $redis)
	{
		var_dump($remember);
	}

}