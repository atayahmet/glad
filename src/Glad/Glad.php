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
    * Constructor
    */
    public function __construct()
    {
        static::$injector = new Injector();
    }

    public function logged()
    {
        static::$injector->inject('Glad\Author','getIdentity');
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
