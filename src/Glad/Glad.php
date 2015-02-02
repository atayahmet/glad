<?php 

namespace Glad;

use Glad\Injector;
use Glad\GladProvider;
use Glad\GladModelInterface;
use Glad\Constants;
use ReflectionObject;

/**
 * Glad authentication container class
 *
 * @author Ahmet ATAY
 * @category Authentication
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class Glad {
    
    /**
    * Injector
    *
    * @var object
    */
    protected static $injector;

    /**
    * Author class name
    *
    * @var object
    */
    protected static $author;
  
    /**
    * Author class name
    *
    * @var object
    */
    protected static $model;

    /**
    * Users table fields
    *
    * @var array
    */
    protected static $fields;
   
    protected static $constants;
    /**
    * Class constructor
    *
    * @return void
    */
    public function __construct()
    {
        self::init();
    }
    
    protected static function init()
    {
        if(is_null(static::$injector)){
            static::$injector = new Injector();
            static::$author = GladProvider::$author;
        }

        if(is_null(static::$model)){
            exit('non model');
        }

        if(is_null(static::$constants)){
            static::$constants = new Constants;
        }

        self::modelAddToInjector(static::$model);
    }

    protected static function modelAddToInjector($model)
    {
        static::$injector->add('GladModelInterface', $model);
    }

    public static function model(GladModelInterface $model)
    {   
        static::$model = $model;

        self::init();
        self::modelAddToInjector(static::$model);

        return new static;
    }

    public static function authField(array $fields)
    {
        static::init();
        static::setStaticVariable(static::$constants, ['field' => 'authFields', 'value' => $fields]);
        static::$injector->add('Constants', static::$constants);

        return new static;
    }

    protected static function setStaticVariable($instance, $parm)
    {
        try {
            $refObject   = new ReflectionObject($instance);
            $refProperty = $refObject->getProperty($parm['field']);
            $refProperty->setAccessible(true);
            $refProperty->setValue(null, $parm['value']);
        }catch(Exception $e){
            exit(var_dump($e));
        }
    }

    public static function __callStatic($method, $parm)
    {
        self::init();

        return static::$injector->inject(static::$author, $method, $parm);
    }

    public function __call($method, $parm)
    {
        self::init();

        return static::$injector->inject(static::$author, $method, $parm);
    }
}