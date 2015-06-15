<?php

namespace Glad;

use Glad\Injector;
use Glad\Cooker;

/**
 * Dependency injection class testss
 *
 * @author Ahmet ATAY
 * @category Dependency Injection
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class InjectorTest extends \PHPUnit_Framework_TestCase
{
	protected $injector;

	public function setUp()
	{
		$this->injector = new Injector;
	}

	/**
     * Test injector/add
     *
     * @return void
     */ 
	public function testAdd()
    {
       $this->injector->add('CookerInterface', 'Glad\Cooker');
       $this->assertTrue($this->injector->has('CookerInterface'));

       $cooker = new Cooker;

       $this->injector->add('CookerInterface', $cooker);
       $this->assertTrue($this->injector->has($cooker));
    }

    /**
     * Test injector/has
     *
     * @return void
     */ 
    public function testHas()
    {
    	$this->injector->add('CookerInterface', 'Glad\Cooker');

    	if($this->injector->has('CookerInterface')) {
    		$reflection = new \ReflectionClass($this->injector);
    		$reflection = $reflection->getProperty('container');
			$reflection->setAccessible(true);
			$container = $reflection->getValue($this->injector);

			$this->assertTrue(isset($container['CookerInterface']));
    	}
    }

    /**
     * Test injector/resolve
     *
     * @return void
     */ 
    public function testResolve()
    {
    	$this->injector->resolve(new Cooker);
    	$this->assertTrue($this->injector->has('Cooker'));
    }

    /**
     * Test injector/get
     *
     * @return void
     */
    public function testGet()
    {
    	$this->injector->add('CookerInterface', 'Glad\Cooker');

        if(is_object($this->injector->get('CookerInterface'))) {
            $this->assertInstanceOf('Glad\Cooker', $this->injector->get('CookerInterface'));
        }else{
            $this->assertTrue($this->injector->get('CookerInterface') =='Glad\Cooker');
        }
    }

     /**
     * Test injector/inject
     *
     * @return void
     */
    public function testInject()
    {
    	$this->injector->add('CookerInterface', 'Glad\Cooker');
     	$result = $this->injector->inject('Glad\Grants\DependenceClass', 'foo');
     	$this->assertInstanceOf('Glad\Cooker', $result);
    }
}