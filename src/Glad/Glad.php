<?php namespace Glad;

use Glad\Injector;

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
