<?php

namespace Glad;

use Glad\Driver\Repository\RepositoryInterface;

class Author {
	public function __construct(RepositoryInterface $std)
	{

	}
	
	public function getIdentity(RepositoryInterface $std, AuthorInterface $auth)
	{
		var_dump($std);
	}
}