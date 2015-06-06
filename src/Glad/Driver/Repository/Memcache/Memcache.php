<?php

namespace Glad\Driver\Repository\Memcache;

use Glad\Interfaces\GladSessionHandlerInterface;
use Glad\Driver\Repository\RepositoryAdapter;
use Glad\Interfaces\CryptInterface;
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
class Memcache extends RepositoryAdapter implements GladSessionHandlerInterface
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
     * memcache instance
     *
     * @var object
     */
    private $memcache;

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
        $this->crypt = $crypt;
        $this->memcache  = new MCache;

        // connect to memcached server
        $this->connect();
        
        
        return true;
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
        $data = $this->memcache->get($this->config['prefix'].$id, false);

        if($this->config['crypt'] === true) {
            $data = json_decode($this->dataDecrypt($data), true);
        }

        return ! $data ? "" : $data;
    }

    /**
     * Save the session data
     *
     * @param $id string
     * @param $data array
     *
     * @return bool
     */
    public function write($id, $data, $refresh = false)
    {
        if($this->config['crypt'] === true) {
            $data = $this->dataCrypt(json_encode($data));
        }

        if(! $refresh) {
            return $this->memcache->add($this->config['prefix'].$id, $data, false, $this->config['timeout']);
        }
        return $this->memcache->replace($this->config['prefix'].$id, $data, false, $this->config['timeout']);
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
        return $this->memcache->delete($this->config['prefix'].$id);
    }

    /**
     * Delete the  all timeout session (not using)
     *
     * @param $maxlifetime integer
     *
     * @return bool
     */
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

    protected function now()
    {
        return time();
    }
}