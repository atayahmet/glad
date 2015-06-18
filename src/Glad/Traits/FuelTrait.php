<?php

namespace Glad\Traits;

trait FuelTrait {
	
	/**
     * Create new user
     *
     * @var $credentials array
     *
     * @return bool|int
     */
    public function gladInsert(array $credentials)
    {
    	return static::forge($credentials)->id;
    }

    /**
     * Update user
     *
     * @var $credentials array
     * @var $where array
     * @return bool
     */
    public function gladUpdate(array $where, array $credentials)
    {
        $entry = static::find_one_by($where);
		$entry->set($credentials);
		return $entry->save();
    }

    /**
     * Get user identity details by identity
     *
     * @var $identity string
     * @return array
     */
    public function getIdentity($identity)
    {
    	$firstData = static::find_one_by($identity);
    	if(! is_null($firstData)) {
    		return $firstData->to_array();
    	}
    }

    /**
     * Get user identity details by user id
     *
     * @var $userId int
     * @return array
     */
    public function getIdentityWithId($userId)
    {
       	$firstData = static::find_one_by('id', $userId);
    	if(! is_null($firstData)) {
    		return $firstData->to_array();
    	}
    }
}