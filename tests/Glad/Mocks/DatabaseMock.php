<?php

namespace Glad\Mocks;

use Glad\Grants\RepositoryHandler;

class DatabaseMock
{
	protected $repository;

	public function __construct()
	{
		$this->repository = new RepositoryHandler;
	}

	public function insert(array $credentials)
	{
		$insertId = isset($credentials['username']) ? md5($credentials['username']) : md5($credentials['email']);
		$this->repository->save('users', [$insertId => $credentials]);
		return $insertId;
	}

	public function update(array $where, array $newData, $limit = 1)
	{
		return $this->repository->update('users', reset($where), $newData);
	}

	public function getIdentity(array $identity)
	{
		$userId = isset($identity['username']) ? md5($identity['username']) : md5($identity['email']);
		return $this->repository->get('users', $userId);
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
		return $this->repository->get('users', $userId);
	}
}