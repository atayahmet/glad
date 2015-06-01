<?php

namespace Glad\Driver\Repository\Memcached;

use Glad\Interfaces\GladSessionHandlerInterface;
use Glad\Driver\Repository\RepositoryAdapter;
use Glad\Interfaces\CryptInterface;
use MemcachedException;
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
class Memcached extends RepositoryAdapter implements GladSessionHandlerInterface
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
    public function openSession(array $config, CryptInterface $crypt)
    {
        $this->config = $config;
        $this->crypt = $crypt;
        $this->memcached  = new MCached;

        // connect to memcached server
        $this->connect();
        
        return true;
    }

    /**
     * It was isolated (not using)
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
        $data = $this->memcached->get($this->config['prefix'].$id);

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
            return $this->memcached->add($this->config['prefix'].$id, $data, $this->now()+$this->config['timeout']);
        }
        return $this->memcached->replace($this->config['prefix'].$id, $data, $this->now()+$this->config['timeout']);
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
        return $this->memcached->delete($this->config['prefix'].$id);
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

    /**
     * Connect to the memcached server
     *
     * @param $exception bool
     *
     * @return bool
     */
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