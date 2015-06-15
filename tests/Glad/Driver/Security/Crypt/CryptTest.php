<?php

namespace Glad\Driver\Security\Crypt;

use Glad\Driver\Security\Crypt\Crypt;
use Glad\Constants;

/**
 * Crypt class tests
 *
 * @author Ahmet ATAY
 * @category Crypt class
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class CryptTest extends \PHPUnit_Framework_TestCase
{
	protected $crypt;
	protected $constants;
	protected $data;

	public function setUp()
	{
		$this->crypt = new Crypt;
		$this->constants = new Constants;
		$this->data = ['name' => 'Ahmet', 'lastname' => 'ATAY'];
	}

	/**
     * Test Crypt/encrypt
     *
     * @return void
     */
	public function testEncrypt()
	{
		$toJson = json_encode($this->data);
		$encryptedData = $this->crypt->encrypt($toJson, $this->constants->secret);
		json_decode($encryptedData, true);
		$this->assertTrue(json_last_error() !== 0);

		$decryptedData = json_decode($this->crypt->decrypt($encryptedData, $this->constants->secret), true);
		$this->assertSame($decryptedData, $this->data);
	}

	/**
     * Test Crypt/decrypt
     *
     * @return void
     */
	public function testDecrypt()
	{
		$toJson = json_encode($this->data);
		$encryptedData = $this->crypt->encrypt($toJson, $this->constants->secret);

		$decryptedData = json_decode($this->crypt->decrypt($encryptedData, $this->constants->secret), true);
		$this->assertSame($decryptedData, $this->data);
	}
}