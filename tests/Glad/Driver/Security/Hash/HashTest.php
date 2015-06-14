<?php

namespace Glad\Driver\Security\Hash;

require __DIR__.'/../../../../../../../../vendor/autoload.php';

use Glad\Driver\Security\Hash\Hash;

/**
 * Hash class tests
 *
 * @author Ahmet ATAY
 * @category Hash class
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class HashTest extends \PHPUnit_Framework_TestCase
{
	protected $hash;
	protected $data;

	public function setUp()
	{
		$this->hash = new Hash;
	}

	/**
     * Test Hash/make
     *
     * @return void
     */
	public function testMake()
	{
		$hashed = $this->hash->make('123412');
		$this->assertFalse($hashed == '123412');
	}

	/**
     * Test Hash/verify
     *
     * @return void
     */
	public function testVerify()
	{
		$hashed = $this->hash->make('123412');
		$this->assertTrue($this->hash->verify('123412', $hashed));
	}
}