<?php

namespace Glad;

use Glad\Conditions;
use Glad\Event\Dispatcher;

/**
 * Conditions class tests
 *
 * @author Ahmet ATAY
 * @category Conditions class
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class ConditionsTest extends \PHPUnit_Framework_TestCase
{
	protected $conditions;
	protected $eventDispatcher;
	protected $userData = [];

	public function setUp()
	{
		$this->conditions = new Conditions;
		$this->eventDispatcher = new Dispatcher;
		$this->userData = ['name' => 'Gandalf', 'banned' => 0];
	}

	/**
     * Test Conditions/add
     *
     * @return void
     */
	public function testAdd()
	{
		$this->conditions->add(['banned' => 1]);
		$reflection = new \ReflectionClass($this->conditions);
		$reflection = $reflection->getProperty('conditions');
		$reflection->setAccessible(true);
		$_conditions = $reflection->getValue($this->conditions);
		$this->assertSame(['banned' => 1], $_conditions);
	}

	/**
     * Test Conditions/apply
     *
     * @return void
     */
	public function testApply()
	{
		$this->conditions->add(['banned' => 1]);
		$banned = $this->conditions->apply($this->userData, [], $this->eventDispatcher);
		$this->assertFalse($banned);

		$this->conditions->add(['banned' => 0]);
		$notBanned = $this->conditions->apply($this->userData, [], $this->eventDispatcher);
		$this->assertTrue($notBanned);
	}

	/**
     * Test Conditions/status
     *
     * @return void
     */
	public function testStatus()
	{
		$this->conditions->add(['banned' => 1]);
		$this->conditions->apply($this->userData, [], $this->eventDispatcher);

		$this->assertSame(['banned' => false], $this->conditions->status());
	}
}