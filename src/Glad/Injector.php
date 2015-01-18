<?php

namespace Glad;

use Glad\GladProvider;
use ReflectionMethod;
use ReflectionClass;

/**
 * Dependency injection class
 *
 * @author Ahmet ATAY
 *
 */
class Injector {
    
    /**
    * Provider
    *
    * @var object
    */
    protected static $gladProvider;

    /**
    * Object Container
    *
    * @var object
    */
    protected static $container;

    public function __construct()
    {
        static::$gladProvider = GladProvider::register();
    }

    public static function inject($class, $method, $parm = null)
    {
      static::setInjectsParameters($class, $method, $parm);
    }

    private static function setInjectsParameters($class, $method, array $parm = null)
    {
        $methods = array('__construct', $method);
        $instance = null;

        foreach($methods as $m) {

            if(method_exists($class, $m)){
                $export = ReflectionMethod::export($class, $m, true);

                preg_match_all('/\[(.*?)\]/', $export, $matches, PREG_PATTERN_ORDER);

                unset($matches[1][0], $matches[1][1]);
                
                $injects = array();

                foreach($matches[1] as $in){

                    $t = preg_split('/ /',$in);
                    $i = end(preg_split("/\\\\/", $t[2]));

                    if(isset(static::$container[$i])){
                        $injects[] = static::$container[$i];
                    }else{
                        $new = new static::$gladProvider[$i];
                        static::$container[$i] = $new;
                        $injects[] = $new;
                    }
                }

                if (is_null($instance)) {
                    $instance = static::newInstanceCurrentClass($class, $injects);
                }else{
                    $reflectionMethod = new ReflectionMethod($class, $m);
                    $reflectionMethod->invokeArgs($instance, $injects);
                }
            }
        
        }
    }

    private static function newInstanceCurrentClass($class, $injects = null)
    {
        if(method_exists($class, '__construct')){
            $c = new ReflectionClass($class);
            $instance = $c->newInstanceArgs($injects);
        }else{
            $instance = new $class();
        }

        return $instance;
    }
}