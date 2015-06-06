<?php

namespace Glad;

require __DIR__.'/../../../../../vendor/autoload.php';

use Glad\Glad;
use Glad\Constants;
use Glad\Injector;
use Glad\GladProvider;

/**
 * Glad container class tests
 *
 * @author Ahmet ATAY
 * @category Container class
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class GladTest extends \PHPUnit_Framework_TestCase
{
	protected $glad;
	protected $constants;
	protected $injector;
	protected $provider;
	protected $repositoryConfig;
	protected $rememberConfig;

	public function setUp()
	{
		$this->glad = new Glad;
		$this->constants = new Constants;
		$this->injector = new Injector;
		$this->provider = new GladProvider;

		$this->repositoryConfig = [
				'path' 	=> __DIR__.'/../../public/Storage',
				'type'	=> 'json',
				'name'	=> 'PHPSESSID',
				'prefix'=> 'ses_',
				'crypt'	=> false,
				'timeout' => 1800
			];

		$this->rememberConfig = [
				'enabled' 	=> 	true,
				'cookieName'=> 'glad',
				'lifetime'	=> (3600*5),
				'field'		=> 'remember_token'
			];
	}

	/**
     * Test Glad/setup
     *
     * @return void
     */
	public function testSetup()
	{
		$result = $this->glad->setup([
				'fields' => [
					'identity' => ['username','email'], 
					'password' => 'password',
				],
				'repository' => [
					'session'  => $this->repositoryConfig
				]
			]);

		$this->assertTrue($result);

		// identity configuration
		$map = ['identity' => ['username', 'email'], 'password' => 'password'];
		$this->assertSame($map, $this->constants->authFields);

		// repository configuration
		$this->assertEquals($this->constants->repository['driver'], 'session');
		$mapSession = $this->repositoryConfig;
		$driverName = $this->constants->repository['driver'];
		$this->assertSame($mapSession, $this->constants->repository['options'][$driverName]);
	}

	/**
     * Test Glad/fields
     *
     * @return void
     */
	public function testFields()
	{
		$result = $this->glad->fields([
				'identity' => ['username'], 
				'password' => 'password',
			]);

		$this->assertTrue($result);

		// identity configuration
		$map = ['identity' => ['username'], 'password' => 'password'];
		$this->assertSame($map, $this->constants->authFields);
	}

	/**
     * Test Glad/repository
     *
     * @return void
     */
	public function testRepository()
	{
		$this->glad->repository(['session' => $this->repositoryConfig]);

		// repository configuration
		$this->assertEquals($this->constants->repository['driver'], 'session');

		$mapSession = $this->repositoryConfig;
		$driverName = $this->constants->repository['driver'];
		$this->assertSame($mapSession, $this->constants->repository['options'][$driverName]);
	}

	/**
     * Test Glad/services
     *
     * @return void
     */
	public function testServices()
	{
		$stub = $this->getMockBuilder('SomeClass')->getMock();
		$this->glad->services(['db' => $stub]);
		$this->assertTrue($this->injector->get('db') instanceof $stub);
	}

	/**
     * Test Glad/remember
     *
     * @return void
     */
	public function testRemember()
	{
		$this->glad->remember($this->rememberConfig);
		$this->assertSame($this->rememberConfig, $this->constants->remember);
	}

	/**
     * Test Glad/domain
     *
     * @return void
     */
	public function testDomain()
	{
		$this->assertEmpty($this->constants->cookieDomain);
		$this->glad->domain('.www.example.com');
		$this->assertSame('.www.example.com', $this->constants->cookieDomain);
	}

	/**
     * Test Glad/provider
     *
     * @return void
     */
	public function testProvider()
	{
		$oldSession = $this->provider->get('SessionHandlerInterface');
		$this->assertFalse($oldSession === 'Glad\Deriver\Repository\Memcache\Memcache');

		$this->glad->provider(['SessionHandlerInterface' => 'Glad\Deriver\Repository\Memcache\Memcache']);
		$newSession = $this->provider->get('SessionHandlerInterface');
		$this->assertTrue($newSession === 'Glad\Deriver\Repository\Memcache\Memcache');
	}

	/**
     * Test Glad/table
     *
     * @return void
     */
	public function testTable()
	{
		$this->assertEquals('users', $this->constants->table);
		$this->glad->table('membership');
		$this->assertEquals('membership', $this->constants->table);
	}
}