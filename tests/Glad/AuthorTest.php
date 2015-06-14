<?php

namespace Glad;

require __DIR__.'/../../../../../vendor/autoload.php';

use Glad\Glad;
use Glad\Author;
use Glad\Constants;
use Glad\Injector;
use Glad\Driver\Security\Crypt\Crypt;
use Glad\Event\Dispatcher;
use Glad\GladProvider;

use Glad\Grants\DatabaseService;
use Glad\Grants\RepositoryHandler;
use Glad\Mocks\CookerMock;
use Glad\Mocks\SessionMock;

/**
 * Author class tests
 *
 * @author Ahmet ATAY
 * @category Author class
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class AuthorTest extends \PHPUnit_Framework_TestCase
{
	protected $glad;
	protected $author;
	protected $injector;
	protected $constants;
	protected $repositoryHandler;
	protected $userData;
	protected $loginData;
	protected $sessionId;

	public function setUp()
	{
		GladProvider::set([
			'DatabaseServiceInterface' => 'Glad\Grants\DatabaseService',
			'SessionHandlerInterface'  => 'Glad\Mocks\SessionMock',
			'CookerInterface'          => 'Glad\Mocks\CookerMock'
		]);

		$this->constants = new Constants;
		$this->sessionId = md5(time());

		$activeDriver = $this->constants->repository['driver'];
		$config = $this->constants->repository['options'][$activeDriver];

		$this->glad = new Glad;
		$this->injector = new Injector;
		$this->repositoryHandler = new RepositoryHandler;
		$this->repositoryHandler->save('cookie', [$config['name'] => $this->sessionId]);
		$this->injector->add('db', 'Glad\Mocks\DatabaseMock');
		$this->author = new Author($this->constants, new CookerMock, $this->injector, new Crypt, new DatabaseService, new SessionMock(), new Dispatcher);

		$this->glad->setup([
			'uniqueField' => 'id',
			'table' => 'users',
			'fields' => [
				'identity' => ['username','email'], 
				'password' => 'password',
			]
		]);

		$this->userData = [
			'id'		=> md5('user@example.com'),
			'firstname' => 'Fisrtname',
			'lastname'	=> 'Lastname',
			'username'	=> 'exampleuser',
			'email'		=> 'user@example.com',
			'password'	=> '123412',
			'banned'	=> 0,
			'activate'	=> 1
		];

		$this->loginData = [
			'email'		=> 'user@example.com',
			'password'	=> '123412'
		];
	}

	/**
     * Test Author/register
     *
     * @return void
     */
	public function testRegister()
	{
		file_put_contents(__DIR__ . '/../storage.json', '');

		$result = $this->glad->register($this->userData);
		$result->andLogin();

		$user = $this->repositoryHandler->get('users', md5('user@example.com'));
		
		$this->assertTrue($result->status() && is_array($user) && count($user) > 0);
	}

	/**
     * Test Author/login
     *
     * @return void
     */
	public function testLogin()
	{
		$this->author->logout();
		
		$this->assertFalse($this->glad->check());

		$this->glad->login($this->loginData);
		
		$this->assertTrue($this->glad->status() && $this->glad->check() && !$this->glad->guest());
	}

	/**
     * Test Author/change
     *
     * @return void
     */
	public function testChange()
	{
		$newData = array_merge($this->userData, ['firstname' => 'Will', 'lastname' => 'Smith']);

		$this->glad->change($newData);
		$user = $this->repositoryHandler->get('users', md5('user@example.com'));
		
		$this->assertTrue($this->glad->status() && $user['firstname'] == 'Will' && $user['lastname'] == 'Smith');
	}

	/**
     * Test Author/apply
     *
     * @return void
     */
	public function testApply()
	{
		$this->glad->logout();
		$this->assertFalse($this->glad->check());

		$this->glad->login($this->loginData);
		$parent = $this;

		// true scenerio
		$this->glad->apply(function(Glad $glad) use($parent) {
			$glad->conditions(['banned' => 0]);
			$glad->event('banned', function(Glad $glad) use($parent) {
				// banned will not work to be zero
				$parent->assertTrue(true);
			});

			$glad->conditions(['activate' => 1]);
			$glad->event('activate', function(Glad $glad) use($parent) {
				// banned will not work to be zero
				$parent->assertTrue(true);
			});
		});

		$this->assertTrue($this->glad->status());

		// false scenerio
		$this->glad->logout();

		$this->glad->apply(function(Glad $glad) use($parent) {
			$glad->conditions(['banned' => 1]);
			$glad->event('banned', function(Glad $glad) use($parent) {
				// banned will not work to be zero
				$parent->assertTrue(true);
			});

			$glad->conditions(['activate' => 0]);
			$glad->event('activate', function(Glad $glad) use($parent) {
				// banned will not work to be zero
				$parent->assertTrue(true);
			});
		});

		$this->assertFalse($this->glad->status());
	}

	/**
     * Test Author/loginByUserId
     *
     * @return void
     */
	public function testLoginByUserId()
	{
		$this->glad->logout();
		$this->assertFalse($this->glad->check());
		$this->glad->loginByUserId(md5('user@example.com'));
		$this->assertTrue($this->glad->status() && $this->glad->check() && !$this->glad->guest());
	}

	/**
     * Test Author/userData
     *
     * @return void
     */
	public function testUserData()
	{
		$userData = $this->glad->userData();
		$this->assertTrue(is_array($userData) && count($userData) > 0);
	}

	/**
     * Test Author/getUserId
     *
     * @return void
     */
	public function testGetUserId()
	{
		$this->assertTrue($this->glad->getUserId() == md5('user@example.com'));
	}

	/**
     * Test Author/check
     *
     * @return void
     */
	public function testCheck()
	{
		$user = $this->repositoryHandler->get('session', $this->sessionId);
		
		$this->assertTrue(unserialize($user)['auth']['status'] === true && $this->glad->check());
	}

	/**
     * Test Author/check
     *
     * @return void
     */
	public function testGuest()
	{
		$this->assertTrue($this->glad->check());
		$this->glad->logout();

		$user = $this->repositoryHandler->get('session', $this->sessionId);	
		$this->assertNull($user);
		$this->assertTrue($this->glad->guest());
	}

	/**
     * Test Author/is
     *
     * @return void
     */
	public function testIs()
	{
		//$result = $this->injector->inject($this->author, 'loginByUserId', [md5('user@example.com')]);
		$this->glad->loginByUserId(md5('user@example.com'));
		$user = $this->repositoryHandler->get('session', $this->sessionId);	
		$this->assertTrue(unserialize($user)['auth']['status'] === true && $this->glad->is('check'));

		$this->assertTrue($this->glad->check());
		$this->glad->logout();

		$user = $this->repositoryHandler->get('session', $this->sessionId);	
		$this->assertNull($user);
		$this->assertTrue($this->glad->is('guest'));
	}

	/**
     * Test Author/toJson
     *
     * @return void
     */
	public function testToJson()
	{
		$this->glad->loginByUserId(md5('user@example.com'));
		$this->assertTrue(is_array(json_decode($this->glad->toJson(), true)));
	}

	/**
     * Test Author/toArray
     *
     * @return void
     */
	public function testToArray()
	{
		$this->assertTrue(is_array($this->glad->toArray()));
	}

	/**
     * Test Author/toObject
     *
     * @return void
     */
	public function testToObject()
	{
		$this->assertTrue(is_object($this->glad->toObject()));
	}

	/**
     * Test Author/toXml
     *
     * @return void
     */
	public function testToXml()
	{
		libxml_use_internal_errors(true);
		$sxe = simplexml_load_string("<?xml version='1.0'>" . $this->glad->toXml());
		$this->assertFalse($sxe);
	}

	/**
     * Test Author/andLogin
     *
     * @return void
     */
	public function testAndLogin()
	{
		$this->glad->logout();
		$this->assertFalse($this->glad->check());

		file_put_contents(__DIR__ . '/../storage.json', '');

		$this->glad->register($this->userData);
		$this->assertTrue($this->glad->status());
		$this->assertFalse($this->glad->check());

		$this->glad->andLogin();
		$this->assertTrue($this->glad->check());
	}

	/**
     * Test Author/status
     *
     * @return void
     */
	public function testStatus()
	{
		$this->glad->logout();

		$this->assertFalse($this->glad->check());
		$this->assertTrue($this->glad->guest());
		
		$this->assertFalse($this->glad->status());
		
		$this->glad->loginByUserId(md5('user@example.com'));
		$this->assertTrue($this->glad->status());
		$user = $this->repositoryHandler->get('session', $this->sessionId);	
		$this->assertTrue(unserialize($user)['auth']['status'] === true && $this->glad->check());
	}
}