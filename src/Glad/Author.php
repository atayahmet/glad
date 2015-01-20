<?php

namespace Glad;

use Glad\Driver\Repository\RepositoryInterface;
use Glad\Driver\Repository\Redis\RedisRepository;
use Glad\Test;

class Author {

	public function __construct(Test $repo)
	{
		 var_dump($repo);
	}
	
	public function getIdentity(RepositoryInterface $session, RepositoryInterface $authh)
	{
		$session->get('hello');
	}

	public function getRepository(RepositoryInterface $repository)
	{
		return $repository;
	}

}