<?php

namespace Glad;

use Glad\GladProvider as Provider;

/**
 * GladProvider testss
 *
 * @author Ahmet ATAY
 * @category Provider
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class GladProviderTest extends \PHPUnit_Framework_TestCase
{
	protected $provider;

	public function setUp()
	{
		$this->provider = new Provider;
	}

	/**
     * Test GladProvider/set
     *
     * @return void
     */ 
	public function testSet()
	{
		$old = $this->provider->get('SessionHandlerInterface');
		$this->assertEquals($old, 'Glad\Driver\Repository\NativeSession\Session');

		$this->provider->set(['SessionHandlerInterface' => 'Glad\Driver\Repository\Memcache\Memcache']);
		$new = $this->provider->get('SessionHandlerInterface');
		$this->assertEquals($new, 'Glad\Driver\Repository\Memcache\Memcache');
	}

	/**
     * Test GladProvider/get
     *
     * @return void
     */ 
	public function testGet()
	{
		$expect = 'Glad\Driver\Repository\Memcache\Memcache';
		$result = $this->provider->get('SessionHandlerInterface');
		$this->assertEquals($expect, $result);
	}
}