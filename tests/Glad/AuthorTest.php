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

class AuthorTest extends \PHPUnit_Framework_TestCase
{
	protected $glad;
	protected $author;
	protected $injector;
	protected $constants;
	protected $repositoryHandler;

	public function setUp()
	{
		GladProvider::set([
			'DatabaseServiceInterface' => 'Glad\Grants\DatabaseService',
			'SessionHandlerInterface'  => 'Glad\Mocks\SessionMock'
		]);

		$this->constants = new Constants;

		$activeDriver = $this->constants->repository['driver'];
		$config = $this->constants->repository['options'][$activeDriver];

		$this->glad = new Glad;
		$this->injector = new Injector;
		$this->repositoryHandler = new RepositoryHandler;
		$this->repositoryHandler->save('cookie', [$config['name'] => md5(time())]);
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
	}

	public function tearDown()
	{
		//file_put_contents(__DIR__ . '/../storage.json', '');
	}

	public function testRegister()
	{
		$result = $this->injector->inject($this->author, 'register', [[
				'firstname' => 'Ali',
				'lastname'	=> 'YILDIZ',
				'username'	=> 'yildizalix',
				'email'		=> 'aliyildix@gmail.com',
				'password'	=> 'ali123412yildiz'
			]]);

		$user = $this->repositoryHandler->get('users', md5('yildizalix'));
		
		$this->assertTrue($result->status() && is_array($user) && count($user) > 0);
	}
}