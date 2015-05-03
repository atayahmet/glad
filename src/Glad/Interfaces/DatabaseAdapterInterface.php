<?php

namespace Glad\Interfaces;

interface DatabaseAdapterInterface {
	
	/**
     * For new data input
     *
     * @param array $credentials
     * @return bool|int
     */ 
	public function insert(array $credentials);
	
	/**
     * To update procedures
     *
     * @param array $credentials
     * @return bool
     */ 
	public function update(array $where, array $newData);

	/**
     * Receives the user information with the user name
     *
     * @param array $user
     * @return array
     */ 
	public function getIdentity($user);

	/**
     * Receives the user information with the user id
     *
     * @param array $user
     * @return array
     */ 
	public function getIdentityWithId($user);
}