<?php

namespace Glad\Driver\Repository\Memcache;

use MemcachedException;
use SessionHandlerInterface;
use Memcache as MCache;

/**
 * Memcache driver
 *
 * @author Ahmet ATAY
 * @category Memcache
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class Memcache implements SessionHandlerInterface
{
    /**
     * $config parameters
     *
     * @var array
     */
	private $config;

    /**
     * memcache instance
     *
     * @var object
     */
    private $memcache;

    /**
     * openning the memcache process
     *
     * @param array $config
     * @param string $prefix
     * 
     * @return bool
     */ 
    public function open($config, $prefix = '')
    {   
        $this->config = $config;

        $this->memcache  = new MCache;

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
        $data = $this->memcache->get($this->config['prefix'].$id, false);
        return ! $data ? "" : $data;
    }

    public function write($id, $data, $refresh = false)
    {
        if(! $refresh) {
            return $this->memcache->add($this->config['prefix'].$id, serialize($data), false, $this->config['timeout']);
        }
        return $this->memcache->replace($this->config['prefix'].$id, serialize($data), false, $this->config['timeout']);
    }

    public function destroy($id)
    {
        return $this->memcache->delete($this->config['prefix'].$id);
    }

    public function gc($maxlifetime)
    {
        return true;
    }

    protected function connect($exception = true)
    {
        $host = $this->config['host'];
        $port = $this->config['port'];
        
        $this->memcache->addServer($host, $port);
        
        if($exception) {
            $stats = $this->memcache->getExtendedStats();

            if(! isset($stats["$host:$port"]) || ! $stats["$host:$port"]) {
                throw new MemcachedException('Can not connect to Memcached server');
            }
        }

        return $this->memcache->connect($host, $port);
    }
}