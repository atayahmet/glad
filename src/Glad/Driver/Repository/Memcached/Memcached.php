<?php

namespace Glad\Driver\Repository\Memcached;

use MemcachedException;
use SessionHandlerInterface;
use Memcached as MCached;

/**
 * Memcache driver
 *
 * @author Ahmet ATAY
 * @category Memcached
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class Memcached implements SessionHandlerInterface
{
    /**
     * $config parameters
     *
     * @var array
     */
    private $config;

    /**
     * memcached instance
     *
     * @var object
     */
    private $memcached;

    /**
     * openning the memcached process
     *
     * @param array $config
     * @param string $prefix
     * 
     * @return bool
     */ 
    public function open($config, $prefix = '')
    {   
        $this->config = $config;

        $this->memcached  = new MCached;

        // connect to memcached server
        $this->connect();
        
        return true;
    }

    public function close()
    {
        return true;
    }

    public function read($id)
    {
        $data = $this->memcached->get($this->config['prefix'].$id);
        return ! $data ? "" : $data;
    }

    public function write($id, $data, $refresh = false)
    {
        if(! $refresh) {
            return $this->memcached->add($this->config['prefix'].$id, $data, $this->now()+$this->config['timeout']);
        }
        return $this->memcached->replace($this->config['prefix'].$id, $data, $this->now()+$this->config['timeout']);
    }

    public function destroy($id)
    {
        return $this->memcached->delete($this->config['prefix'].$id);
    }

    public function gc($maxlifetime)
    {
        return true;
    }

    protected function connect($exception = true)
    {
        $host = $this->config['host'];
        $port = $this->config['port'];
        
        $this->memcached->addServer($host, $port);

        if($exception) {
            $stats = $this->memcached->getStats();

            if(! isset($stats["$host:$port"]) || ! $stats["$host:$port"]) {
                throw new MemcachedException('Can not connect to Memcached server');
            }
            return false;
        }
        return true;
    }
}