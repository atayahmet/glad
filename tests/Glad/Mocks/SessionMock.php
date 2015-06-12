<?php

namespace Glad\Mocks;

use Glad\Interfaces\GladSessionHandlerInterface;
use Glad\Interfaces\CryptInterface;
use Glad\Driver\Security\Crypt\Crypt;
use Glad\Driver\Repository\RepositoryAdapter;
use Glad\Constants;
use Glad\Grants\RepositoryHandler;

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

	protected $repositoryHandler;
	/**
     * start the session process
     *
     * @param $config array
     * @param $prefix null
     *
     * @return bool
     */

	public function __construct()
	{
		$this->repositoryHandler = new RepositoryHandler;
		$this->crypt = new Crypt;
		$constants = new Constants;
		$this->config = $constants->repository['options'][$constants->repository['driver']];
	}

	public function openSession(array $config, CryptInterface $crypt)
	{
		
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
        $data = $this->repositoryHandler->get('session', $id);

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
    	//exit(var_dump($id));
    	$data['timestamp'] = $this->now();
    	$data['expiration']	= $this->config['timeout'];

    	$sessionData = $this->dataCrypt($this->serializer($data));

    	return $this->repositoryHandler->save('session', [$id => $sessionData]);
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
    	return $this->repositoryHandler->remove('session', $id);
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
        return true;
    }
}