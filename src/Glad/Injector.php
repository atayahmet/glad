<?php

namespace Glad;

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
class Injector
{
    /**
     * Provider
     *
     * @var array
     */
    public static $providers = [];
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
        //static::$gladProvider = GladProvider::get();
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
    public static function inject($class, $method = null, array $parm = null)
    {
        return static::setInjectsParameters($class, $method, $parm);
    }

    /**
     * Check the instance on container
     *
     * @param string|object $keyOrClass
     *
     * @return void
     */ 
    public static function has($keyOrClass)
    {
        if(is_object($keyOrClass)) {
            foreach(self::$container as $key => $class) {
                if(is_object($class) && $class instanceof $keyOrClass) {
                    return true;
                }
            }
        }else{
            return isset(self::$container[$keyOrClass]) ? true : false;
        }
    }

    /**
     * The class add to container
     *
     * @param string $key
     * @param object|string $class
     *
     * @return void
     */ 
    public static function add($key, $class)
    {
        self::$container[$key] = $class;
    }

    /**
     * Resolve the object and add
     *
     * @param object $class
     *
     * @return void
     */ 
    public static function resolve($class = null)
    {
        if(is_object($class)){

            $resolved = preg_split('/\\\\/', get_class($class));

            if(count($resolved) > 0) {
                static::add(end($resolved), $class);
            }
        }
    }

    /**
     * Get the instance from object container
     *
     * @param string $name
     *
     * @return object
     */ 
    public static function get($name)
    {
        return self::$container[$name];
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
    protected static function setInjectsParameters($class, $method, array $parm = null)
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

                $parm = static::editParameter($matches[1], $parm);
               
                // Yeni enjekte edilecek parametrelerin toplanacağı dizi değişkeni.
                $injects = array();

                foreach($matches[1] as $in){

                    $t = preg_split('/ /',$in);
                    $segments = preg_split("/\\\\/", $t[2]);
                    $last = end($segments);
   
                    if(isset(static::$container[$last])){
                        $injects[] = !is_object(static::$container[$last]) ? new static::$container[$last] : static::$container[$last];
                    }
                    
                    elseif(isset(static::$providers[$last])){
                        $new = new static::$providers[$last];

                        static::$container[$last] = $new;
                        
                        $injects[] = $new;
                        
                    }else{

                        if($last == 'array' || strpos($last, '$') !== false || $last == 'Closure'){
                            foreach($parm as $_parm){
                                $injects[] = $_parm;
                            }

                            $parm = [];
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
                    $reflectionMethod->setAccessible(true);

                    // Geçerli method parametreler enjekte edilerek çalıştırılıyor..
                    return $reflectionMethod->invokeArgs($instance, $injects);
                }
            }        
        }

        $c = new ReflectionClass($class);
        return $c->newInstanceWithoutConstructor();
    }

    /**
     * New instance creator
     *
     * @param string $class
     * @param array $injects
     *
     * @return object
     */ 
    protected static function newInstanceCurrentClass($class, array $injects = null)
    {
        if(method_exists($class, '__construct')){
            $c = new ReflectionClass($class);
            $instance = $c->newInstanceArgs($injects);
        }else{
            $instance = new $class();
        }

        return $instance;
    }

    /**
     * Edit that run method parameters
     *
     * @param array $depends
     * @param array $params
     *
     * @return array
     */ 
    protected static function editParameter($depends, $params)
    {
        if(count($depends) > 0 && count($params) > 0){
            foreach ($depends as $key => $value) {
                if(!preg_match('/array(\s+)\$(.*?)/', $value) && !preg_match('/\$(.*?)(\s+)\=(\s+)(.*?)/', $value)){
                    unset($depends[$key]);
                }
            }

            if(count($depends) == count($params)) return $params;

            $depends = array_values($depends);
            $params  = array_values($params);

            foreach($depends as $key => $dep){
                if(!isset($params[$key])){
                    $newParm = preg_split('/\=/',$dep);
                    
                    if(count($newParm) > 1){
                        $newParm = trim(array_filter($newParm)[1]);
                    }

                    if(! is_array($newParm) && strtolower($newParm) == 'false'){
                        $params[] = false;
                    }
                    elseif(! is_array($newParm) && strtolower($newParm) == 'true'){
                        $params[] = true;
                    }else{
                        $params[] = $newParm;
                    }
                }
            }
        }
        return $params;
    }
}
