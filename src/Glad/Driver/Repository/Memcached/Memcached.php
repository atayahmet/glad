<?php

namespace Glad\Driver\Repository\Memcached;

use SessionHandlerInterface;
use Memcached as MCached;

/**
 * Memcached driver
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
	private $savePath;
	private $prefix;

    public function open($savePath, $prefix)
    {
        $this->savePath = $savePath;
        $this->prefix = $prefix;

        if (!is_dir($this->savePath)) {
            mkdir($this->savePath, 0777);
        }

        return true;
    }

    public function close()
    {
        return true;
    }

    public function read($id)
    {
        return (string)@file_get_contents("$this->savePath/$this->prefix".$id);
    }

    public function write($id, $data)
    {
        return file_put_contents("$this->savePath/$this->prefix".$id, serialize($data)) === false ? false : true;
    }

    public function destroy($id)
    {
        $file = "$this->savePath/$this->prefix".$id;
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }

    public function gc($maxlifetime)
    {
        foreach (glob("$this->savePath/$this->prefix*") as $file) {
            if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
                unlink($file);
            }
        }

        return true;
    }
}