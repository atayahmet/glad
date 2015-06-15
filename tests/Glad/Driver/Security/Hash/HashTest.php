<?php

namespace Glad\Driver\Security\Hash;

use Glad\Driver\Security\Hash\Hash;
use Glad\Constants;

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
	protected $constants;
	protected $data;

	public function setUp()
	{
		$this->constants = new Constants;
		$this->hash = new Hash;
	}

	/**
     * Test Hash/make
     *
     * @return void
     */
	public function testMake()
	{
		$hashed = $this->hash->make('123412', $this->constants->cost);
		$this->assertFalse($hashed == '123412');
	}

	/**
     * Test Hash/verify
     *
     * @return void
     */
	public function testVerify()
	{
		$hashed = $this->hash->make('123412', $this->constants->cost);
		$this->assertTrue($this->hash->verify('123412', $hashed));
	}
}