<?php

namespace Glad\Mocks;

use Glad\Grants\RepositoryHandler;

class DatabaseMock
{
	protected $repository;

	public function __construct()
	{
		$this->repository = new RepositoryHandler;

		$this->repository->update('users', '55773fc317897', ['name' => 'mehmet', 'surname' => 'yıldız', 'email' => 'ahmet.atay@hotmail.com']);
	}

	public function insert(array $credentials)
	{
		$insertId = uniqid();
		$this->repository->save('users', [$insertId => $credentials]);
		return $insertId;
	}

	public function update(array $where, array $newData, $limit = 1)
	{
		return $this->repository->update('users', reset($where), $newData);
	}

	public function getIdentity(array $identity)
	{
		
	}

	/**
     * Get the user identity with user id
     *
     * @param mixed $userId
     *
     * @return array
     */
	public function getIdentityWithId($userId)
	{
		
	}
}