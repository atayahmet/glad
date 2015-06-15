<?php

namespace Glad\Event;

use Glad\Glad;
use Glad\Event\Dispatcher;

/**
 * Dispatcher class tests
 *
 * @author Ahmet ATAY
 * @category Dispatcher class
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class DispatcherTest extends \PHPUnit_Framework_TestCase
{
	protected $dispatcher;
	protected $closure;

	public function setUp()
	{
		$this->dispatcher = new Dispatcher;

		$this->closure = function() {
			return 'error';
		};
	}

	/**
     * Test Author/set
     *
     * @return void
     */
	public function testSet()
	{
		$this->dispatcher->set('error', $this->closure);

		$reflection = new \ReflectionClass($this->dispatcher);
    	$reflection = $reflection->getProperty('_events');
		$reflection->setAccessible(true);
		$events = $reflection->getValue($this->dispatcher);

		$this->assertTrue(isset($events['error']));
		$this->assertTrue($this->closure instanceof $events['error']);
	}

	/**
     * Test Author/run
     *
     * @return void
     */
	public function testRun()
	{
		$this->dispatcher->set('error', $this->closure);
		$this->assertEquals($this->dispatcher->run('error'), 'error');
	}

	/**
     * Test Author/has
     *
     * @return void
     */
	public function testHas()
	{
		$this->dispatcher->set('error', $this->closure);
		$this->assertTrue($this->dispatcher->has('error'));
	}
}