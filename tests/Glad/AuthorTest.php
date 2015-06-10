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
use Glad\Mocks\CookerMock;
use Glad\Mocks\SessionMock;

class AuthorTest extends \PHPUnit_Framework_TestCase
{
	protected $glad;
	protected $author;
	protected $injector;

	public function setUp()
	{
		GladProvider::set([
			'DatabaseServiceInterface' => 'Glad\Grants\DatabaseService',
			'SessionHandlerInterface'  => 'Glad\Mocks\SessionMock'
		]);

		$this->glad = new Glad;
		$this->injector = new Injector;

		$this->injector->add('db', 'Glad\Mocks\DatabaseMock');
		$this->author = new Author(new Constants, new CookerMock, $this->injector, new Crypt, new DatabaseService, new SessionMock, new Dispatcher);

		$this->glad->setup([
			'uniqueField' => 'id',
			'table' => 'users',
			'fields' => [
				'identity' => ['username','email'], 
				'password' => 'password',
			]
		]);
	}

	public function testRegister()
	{
		$result = $this->injector->inject($this->author, 'register', [[
				'firstname' => 'Ali',
				'lastname'	=> 'YILDIZ',
				'username'	=> 'yildizalixxxx',
				'email'		=> 'aliyildizxxxx@gmail.com',
				'password'	=> 'ali123412yildiz'
			]]);

		exit(var_dump($result->status()));
	}
}