<?php

namespace GLad\Mocks;

use Glad\Interfaces\GladSessionHandlerInterface;
use Glad\Interfaces\CryptInterface;
use Glad\Driver\Repository\RepositoryAdapter;

class SessionMock extends RepositoryAdapter implements GladSessionHandlerInterface
{
	/**
     * $config parameters
     *
     * @var array
     */
	protected $config;
	
	/**
     * $crypt instance
     *
     * @var object
     */
	protected $crypt;

	/**
     * start the session process
     *
     * @param $config array
     * @param $prefix null
     *
     * @return bool
     */
	public function openSession(array $config, CryptInterface $crypt)
	{
		$this->config = $config;
    	$this->crypt  = $crypt;

        if(!is_dir($this->config['path'])) {
            mkdir($this->config['path'], 0777);
        }

        $this->gc($this->config['timeout']);
	}

	/**
     * It was isolated
     *
     * @param $config array
     * @param $prefix null
     *
     * @return bool
     */
    public function open($config, $prefix = '')
    {
        return true;
    }

    /**
     * close the session process (not using)
     *
     * @return bool
     */
    public function close()
    {
        return true;
    }

    /**
     * Read the session data
     *
     * @param $id string
     *
     * @return null|array
     */
    public function read($id)
    {
        $data = (string)@file_get_contents($this->config['path']."/".$this->config['prefix'].$id);

        if($data) {
        	$data = $this->unserializer($this->dataDecrypt($data));

        	if($data['timestamp'] + $data['expiration'] < $this->now()) {
        		return null;
        	}
        }
        return $data;
    }

    /**
     * Save the session data
     *
     * @param $id string
     * @param $data array
     *
     * @return bool
     */
    public function write($id, $data)
    {
    	$data['timestamp'] 	= $this->now();
    	$data['expiration']	= $this->config['timeout'];

    	$sessionData = $this->dataCrypt($this->serializer($data));

        return file_put_contents($this->config['path']."/".$this->config['prefix'].$id, $sessionData) === false ? false : true;
    }

    /**
     * Delete the session data
     *
     * @param $id string
     *
     * @return bool
     */
    public function destroy($id)
    {
        $file = $this->config['path']."/".$this->config['prefix'].$id;
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }

    /**
     * Delete the  all timeout session
     *
     * @param $maxlifetime integer
     *
     * @return bool
     */
    public function gc($maxlifetime)
    {
        foreach (glob($this->config['path']."/".$this->config['prefix']."*") as $file) {
            if (filemtime($file) + $maxlifetime < $this->now() && file_exists($file)) {
                unlink($file);
            }
        }
        return true;
    }
}