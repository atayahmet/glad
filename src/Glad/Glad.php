<?php 

namespace Glad;

use Glad\Injector;

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
    private static $injector;

    /**
    * Author class name
    *
    * @var string
    */
    private static $author = 'Glad\Author';
  
    /**
    * Constructor
    */
    public function __construct()
    {
        static::$injector = new Injector();
    }

    public function login( array $parm = null)
    {
        return static::$injector->inject(static::$author, __METHOD__, $parm);
    }

    public function register()
    {

    }

    public function is()
    {

    }

    public function check()
    {

    }

    public function guest()
    {
        static::$injector->inject(static::$author, 'destroy');
    }


    public function logged()
    {
        static::$injector->inject(static::$author, 'getIdentity');
    }
    
    public static function __callStatic($w, $x)
    {
      exit(var_dump(func_get_args()));
    }

    public function __call($x, $y)
    {
      
      exit;
      exit(var_dump(func_get_args()));
    }
}
