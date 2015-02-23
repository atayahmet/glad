<?php 

namespace Glad;

use Glad\Injector;
use Glad\GladProvider;
use Glad\Model\GladModelInterface;
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
    * Model object
    *
    * @var object
    */
    protected static $model;

    /**
    * Users table fields
    *
    * @var array
    */
    protected static $identityFields;
   
    /**
    * Constants class instance 
    *
    * @var object
    */
    protected static $constants;

    protected static $provider;
    /**
    * Class constructor
    *
    * @return void
    */
    public function __construct()
    {
        self::init();
    }
    
    /**
    * Class initializer
    *
    * @return void
    */
    protected static function init()
    {
        if(is_null(static::$injector)){
            static::$injector = new Injector();
            static::$injector->add('Injector', static::$injector);

            static::$author = GladProvider::$author;
        }

        // check and set constants class instance and add to injector
        static::setConstantsInstance();
    }

    /**
    * Model instance add to injector
    *
    * @param $model object
    * @return void
    */
    protected static function modelAddToInjector($model)
    {
        static::$injector->add('GladModelInterface', $model);
    }

    public static function setup(array $config)
    {
        $thisInstance = new static;

        foreach($config as $name => $parm){
            if(method_exists($thisInstance, $name)){
                static::$name($parm);
            }
        }
    }

    /**
    * set identity fields
    *
    * @param $fields array
    * @return void
    */
    public static function fields($fields)
    {
        static::init();

        if(!is_array($fields)){
            $fields = array($fields);
        }

        static::setStaticVariable(static::$constants, ['field' => 'authFields', 'value' => $fields]);
        static::$identityFields = true;
    }

    /**
    * set model data object
    *
    * @param $model GladModelInterface instance
    * @return void
    */
    public static function model(GladModelInterface $model)
    {   
        static::$model = $model;

        self::init();
        self::modelAddToInjector(static::$model);
    }

    /**
    * set user table name
    *
    * @param $table string
    * @return void
    */
    public static function table($table)
    {
        static::init();

        static::setStaticVariable(static::$constants, ['field' => 'table', 'value' => $table]);
    }

    /**
    * set user table name
    *
    * @param $table string
    * @return void
    */
    protected static function setConstantsInstance()
    {
        if(! is_object(static::$constants)){
            static::$constants = static::getConstantsInstance();
            static::$injector->resolve(static::$constants);
        }
    }

    protected static function checkIdentityField()
    {
        if(! static::$identityFields){
            throw new \Exception("Identity fields error", 1);
        }

        return true;
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

    protected static function getConstantsInstance()
    {
        return is_null(static::$constants) ? new Constants : static::$constants;
    }

    public static function __callStatic($method, $parm)
    {
        static::checkIdentityField();
        
        self::init();

        return static::$injector->inject(static::$author, $method, $parm);
    }

    public function __call($method, $parm)
    {
        static::checkIdentityField();

        self::init();

        return static::$injector->inject(static::$author, $method, $parm);
    }
}