<?php 

namespace Glad;

use Glad\Injector;
use Glad\GladProvider;
use Glad\Interfaces\DatabaseAdapterInterface;
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
class Glad
{
    
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
            static::provider([]);
        }

        // check and set constants class instance and add to injector
        static::setConstantsInstance();
    }

    /**
     * Model instance add to injector
     *
     * @param $model object
     *
     * @return void
     */
    protected static function modelAddToInjector($model)
    {
        static::$injector->add('DatabaseAdapterInterface', $model);
    }

    /**
     * Initializer
     *
     * @param $config array
     *
     * @return bool
     */
    public static function setup(array $config)
    {
        $thisInstance = new static;

        foreach($config as $name => $parm){
            if(method_exists($thisInstance, $name)){
                static::$name($parm);
            }
        }

        return true;
    }

    /**
     * set identity fields
     *
     * @param $fields array
     *
     * @return bool
     */
    public static function fields($fields)
    {
        static::init();

        if(!is_array($fields)){
            $fields = array($fields);
        }

        static::setStaticVariable(static::$constants, ['field' => 'authFields', 'value' => $fields]);
        static::$identityFields = true;

        return true;
    }

    /**
     * Set services
     *
     * @param $services array
     *
     * @return bool
     */
    public static function services(array $services)
    {
        static::$injector->add('DatabaseService', new Services\DatabaseService);

        foreach($services as $name => $instance) {
            static::$injector->add($name, $instance);
        }

        return true;
    }

    /**
     * Set remember parameters
     *
     * @param $remember array
     *
     * @return bool
     */
    public static function remember(array $remember)
    {
        static::setStaticVariable(static::$constants,
            [
                'field' => 'remember',
                'value' => array_merge(static::$constants->remember, $remember)
            ]
        );

        return true;
    }

    /**
     * Set session repository parameters
     *
     * @param $repository array
     *
     * @return bool
     */
    public static function repository(array $repository)
    {
        $driver = key($repository);
        $config = static::$constants->repository;

        if(! isset($config['options'][$driver])) {
            $config['options'][$driver] = $repository;
        }else{
            $config['options'][$driver] = array_merge($config['options'][$driver], reset($repository));
        }
        $config['driver'] = $driver;
        
        static::setStaticVariable(static::$constants,
            [
                'field' => 'repository', 
                'value' => $config
            ]
        );

        return true;
    }

    /**
     * Set cookie domain paremeter
     *
     * @param $domain string
     *
     * @return bool
     */
    public static function domain($domain = false)
    {
        if($domain) {
            static::setStaticVariable(static::$constants,
                [
                    'field' => 'cookieDomain',
                    'value' => $domain
                ]
            );

            return true;
        }
    }

    /**
     * Set custom providers
     *
     * @param $providers array
     *
     * @return bool
     */
    public static function provider(array $providers)
    {
        GladProvider::set($providers);
        
        static::setStaticVariable(static::$injector,
            [
                'field' => 'providers',
                'value' => GladProvider::get()
            ]
        );

        return true;
    }

    /**
     * set user table name
     *
     * @param $table string
     *
     * @return bool
     */
    public static function table($table = false)
    {
        if($table) {
            static::init();
            static::setStaticVariable(static::$constants, ['field' => 'table', 'value' => $table]);

            return true;
        }
    }

    public static function uniqueField($fieldName = false)
    {
        if($fieldName) {
            static::init();
            static::setStaticVariable(static::$constants, ['field' => 'id', 'value' => $fieldName]);

            return true;
        }
    }

    /**
     * Set the Glad\Constants class instance
     *
     * @return void
     */
    protected static function setConstantsInstance()
    {
        if(! is_object(static::$constants)){
            static::$constants = static::getConstantsInstance();
            static::$injector->resolve(static::$constants);
        }
    }

    /**
     * Check the identity fields
     *
     * @return bool
     */
    protected static function checkIdentityField()
    {
        if(! static::$identityFields){
            throw new \Exception("Identity fields error", 1);
        }

        return true;
    }

    /**
     * Set the static and protected variables
     *
     * @param $instance object will set the class
     * @param $parm array
     *
     * @return bool
     */
    protected static function setStaticVariable($instance, array $parm)
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

    /**
     * Get the Glad\Constants class instance
     *
     * @return bool
     */
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