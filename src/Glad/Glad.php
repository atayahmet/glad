<?php 

namespace Glad;

use Glad\Injector;
use Glad\Author;
use ReflectionMethod;
use ReflectionClass;
use stdClass;
use Glad\Driver\Repository\NativeSession\Session;

/**
 * Glad authentication container class
 *
 * @author Ahmet ATAY
 */
class Glad {
    
    private static $injector;
  
    public function __construct()
    {
        static::$injector = new Injector();

        $refParam = new \ReflectionParameter(array('Glad\Author', 'getIdentity'), 1);
exit($refParam->name);
$export = \ReflectionParameter::export(
   array(
      $refParam->getDeclaringClass()->name, 
      $refParam->getDeclaringFunction()->name
   ), 
   $refParam->name, 
   true
);
exit(var_dump($refParam));
$type = preg_replace('/.*?(\w+)\s+\$'.$refParam->name.'.*/', '\\1', $export);
exit($type);


        
    }

    public function logged()
    {

    }
    
    public static function __callStatic($w, $x)
    {
      exit(var_dump(func_get_args()));
    }

    public function __call($x, $y)
    {
      $this->injector->get();
      exit;
      exit(var_dump(func_get_args()));
    }
}
