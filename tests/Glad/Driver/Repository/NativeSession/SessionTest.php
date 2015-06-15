<?php

namespace Glad\Driver\Repository\NativeSession;;

use Glad\Driver\Repository\NativeSession\Session;
use Glad\Driver\Security\Crypt\Crypt;

/**
 * Session class tests
 *
 * @author Ahmet ATAY
 * @category Session class
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class SessionTest extends \PHPUnit_Framework_TestCase
{
	protected $session;

	public function setUp()
	{
		$this->session = new Session;
		$this->session->openSession(
			[
				'path'   => __DIR__ . '/../../../resource/',
				'type'   => 'serialize',
				'name' 	 => 'SESSIONID',
				'prefix' => 'ses_',
				'crypt'	 => false,
				'timeout'=> 1800
			], new Crypt);
	}

	/**
     * Test Session/write
     *
     * @return void
     */
	public function testWrite()
	{
		$this->assertTrue($this->session->write('test', ['hello']));
		$data = unserialize(file_get_contents(__DIR__ . '/../../../resource/ses_test'));
		
		$this->assertTrue(is_array($data));
		$this->assertSame('hello', reset($data));
	}

	/**
     * Test Session/read
     *
     * @return void
     */
	public function testRead()
	{
		$this->session->write('test', ['hello']);
		$data = $this->session->read('test');

		$this->assertTrue(is_array($data));
		$this->assertSame('hello', reset($data));
	}

	/**
     * Test Session/destroy
     *
     * @return void
     */
	public function testDestroy()
	{
		$this->session->write('test', ['hello']);
		$data = $this->session->read('test');
		$this->assertTrue(is_array($data));

		$this->assertTrue($this->session->destroy('test'));
		$this->assertEmpty($this->session->read('test'));
	}
}