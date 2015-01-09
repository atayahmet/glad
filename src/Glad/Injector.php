<?php

namespace Glad;

use GladProvider;

/**
 * Dependency injection class
 *
 * @author Ahmet ATAY
 *
 */
class Injector {
  
    private static $gladProvider;

    public function __construct()
    {
        static::$gladProvider = GladProvider::register();   
    }

    public static function get()
    {
        if(is_null(static::$gladProvider)){
            self::__construct();
        }
    }

    return static::$gladProvider;
}
