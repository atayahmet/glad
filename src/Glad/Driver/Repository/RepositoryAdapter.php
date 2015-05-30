<?php

namespace Glad\Driver\Repository;

class RepositoryAdapter {

	/**
     * Data serializer
     *
     * @param $data array
     *
     * @return string
     */
    protected function serializer(array $data)
    {
    	if($this->config['type'] == 'serialize') {
    		return serialize($data);
    	}
    	return json_encode($data);
    }

    /**
     * Data unserializer
     *
     * @param $data string
     *
     * @return array
     */
    protected function unserializer($data)
    {
    	if($this->config['type'] == 'serialize') {
    		return unserialize($data);
    	}
    	return json_decode($data, true);
    }

    /**
     * Current timestamp
     *
     * @return integer
     */
    protected function now()
    {
    	return time();
    }

    /**
     * Encrypt the session data
     *
     * @param $data string
     *
     * @return string
     */
    protected function dataCrypt($data)
    {
    	if($this->config['crypt'] === true) {
    		$data = $this->crypt->encrypt($data);	
    	}
    	return $data;
    }

    /**
     * Decrypt the session data
     *
     * @param $data string
     *
     * @return string
     */
    protected function dataDecrypt($data)
    {
    	if($this->config['crypt'] === true) {
    		$data = $this->crypt->decrypt($data);	
    	}
    	return $data;
    }
}