<?php

namespace Glad;

require __DIR__.'/../../../../../vendor/autoload.php';

use Glad\Glad;
use Glad\Author;
use Glad\Constants;
use Glad\Injector;
use Glad\Driver\Security\Crypt\Crypt;
use Glad\Event\Dispatcher;

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
		$this->glad = new Glad;
		$this->injector = new Injector;

		$this->injector->add('db', 'Glad\Mocks\DatabaseMock');
		$this->author = new Author(new Constants, new CookerMock, $this->injector, new Crypt, new DatabaseService, new SessionMock, new Dispatcher);
	}

	public function testRegister()
	{

	}
}