<?php

namespace Glad;

require __DIR__.'/../../../../../vendor/autoload.php';

use Glad\Glad;
use Glad\Author;
use Glad\Constants;

use tests\Mocks\CookerMock;

class AuthorTest extends \PHPUnit_Framework_TestCase
{
	protected $glad;
	protected $author;

	public function setUp()
	{
		new CookerMock;
		$this->glad = new Glad;
		//$this->author = new Author(new Constants);
	}

	public function testRegister()
	{

	}
}