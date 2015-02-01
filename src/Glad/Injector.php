<?php

namespace Glad;

use Glad\GladProvider;
use ReflectionMethod;
use ReflectionClass;

/**
 * Dependency injection class
 *
 * @author Ahmet ATAY
 * @category Authentication
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
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

    /**
    * Class constructor
    *
    * @return void
    */    
    public function __construct()
    {
        static::$gladProvider = GladProvider::register();
    }

    /**
    * Inject method
    *
    * @param string $class
    * @param string $method
    * @param array $parm
    *
    * @return void
    */   
    public static function inject($class, $method, array $parm = null)
    {
        return static::setInjectsParameters($class, $method, $parm);
    }

    public static function add($key, $class)
    {
        self::$container[$key] = $class;
    }
    
    /**
    * method setInjectsParameters
    *
    * @param string $class
    * @param string $method
    * @param array $parm
    *
    * @return all types
    */ 
    private static function setInjectsParameters($class, $method, array $parm = null)
    {
        $methods = array('__construct', $method);
        $instance = null;

        foreach($methods as $k => $m) {

            if(method_exists($class, $m)){

                $export = ReflectionMethod::export($class, $m, true);

                // Parametreler ayıklanıyor.
                preg_match_all('/\[(.*?)\]/', $export, $matches, PREG_PATTERN_ORDER);

                // Gereksiz değerler diziden çıkarılıyor
                unset($matches[1][0], $matches[1][1]);
                
                // Yeni enjekte edilecek parametrelerin
                // toplanacağı dizi değişkeni.
                $injects = array();

                foreach($matches[1] as $in){

                    $t = preg_split('/ /',$in);
                    $segments = preg_split("/\\\\/", $t[2]);
                    $last = end($segments);

                    if($last == 'array'){
                        foreach($parm as $_parm){
                            $injects[] = $_parm;
                        }
                    }else{
                        if(isset(static::$container[$last])){
                            $injects[] = static::$container[$last];
                        }else{
                            if(isset(static::$gladProvider[$last])){
                                $new = new static::$gladProvider[$last];

                                static::$container[$last] = $new;
                                
                                $injects[] = $new;
                            }
                        }
                    }
                }

                // Geçerli class'ın __construct methodu var ise parametreler enjekte
                // ediliyor ve instance alınıyor..
                if(method_exists($class, '__construct') && is_null($instance)){

                    $c = new ReflectionClass($class);
                    $instance = $c->newInstanceArgs($injects);
                }else{
                    if(is_null($instance)){
                        $instance = new $class();
                    }

                    $reflectionMethod = new ReflectionMethod($class, $m);

                    // Geçerli method parametreler enjekte edilerek çalıştırılıyor..
                    return $reflectionMethod->invokeArgs($instance, $injects);
                }
            }
        
        }
    }

    /**
    * New instance creator
    *
    * @param string $class
    * @param array $injects
    *
    * @return object
    */ 
    private static function newInstanceCurrentClass($class, array $injects = null)
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
