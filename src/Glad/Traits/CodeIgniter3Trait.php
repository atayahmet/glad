<?php

namespace Glad\Traits;

trait CodeIgniter3Trait {
	
	/**
     * Create new user
     *
     * @var $credentials array
     *
     * @return bool|int
     */
    public function gladInsert(array $credentials)
    {
        $this->db->insert($this->table, $credentials);
        return $this->db->affected_rows() > 0 ? $this->db->insert_id() : null;
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
        $this->db->where($where)->update($this->table, $credentials);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Get user identity details by identity
     *
     * @var $identity string
     * @return array
     */
    public function getIdentity($identity)
    {
        $user = $this->db->where($identity)->get($this->table);

        if($user->num_rows() > 0) {
            return $user->row_array();
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
        $user = $this->db->where($this->id, $userId)->get($this->table);

        if($user->num_rows() > 0) {
            return $user->row_array();
        }
    }
}