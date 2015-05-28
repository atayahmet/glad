<?php

namespace Glad\Driver\Repository\NativeSession;

use SessionHandlerInterface;

/**
 * Native Session driver
 *
 * @author Ahmet ATAY
 * @category Session
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class Session implements SessionHandlerInterface
{
	private $config;

    public function open($config, $prefix)
    {
    	$this->config = $config;

        if(!is_dir($this->config['path'])) {
            mkdir($this->config['path'], 0777);
        }

        return true;
    }

    public function close()
    {
        return true;
    }

    public function read($id)
    {
    	$this->gc($this->config['timeout']);

        $data = (string)@file_get_contents($this->config['path']."/".$this->config['prefix'].$id);

        if($data) {
        	$data = $this->unserializer($data);

        	if($data['timestamp'] + $data['expiration'] < $this->now()) {
        		return null;
        	}
        }
        return $data;
    }

    public function write($id, $data)
    {
    	$data['timestamp'] 	= $this->now();
    	$data['expiration']	= $this->config['timeout'];
        return file_put_contents($this->config['path']."/".$this->config['prefix'].$id, $this->serializer($data)) === false ? false : true;
    }

    public function destroy($id)
    {
        $file = $this->config['path']."/".$this->config['prefix'].$id;
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }

    public function gc($maxlifetime)
    {
        foreach (glob($this->config['path']."/".$this->config['prefix']."*") as $file) {
            if (filemtime($file) + $maxlifetime < $this->now() && file_exists($file)) {
                unlink($file);
            }
        }

        return true;
    }

    protected function serializer(array $data)
    {
    	if($this->config['type'] == 'serialize') {
    		return serialize($data);
    	}
    	return json_encode($data);
    }

    protected function unserializer($data)
    {
    	if($this->config['type'] == 'serialize') {
    		return unserialize($data);
    	}
    	return json_decode($data, true);
    }

    protected function now()
    {
    	return time();
    }
}