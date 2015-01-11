<?php

namespace Glad;

use Glad\GladProvider;

/**
 * Dependency injection class
 *
 * @author Ahmet ATAY
 *
 */
class Injector {
  
    protected static $gladProvider;
    protected static $container;

    public function __construct()
    {
        static::$gladProvider = GladProvider::register();

        // var_dump(static::$gladProvider);
    }

    public static function get()
    {
        if(is_null(static::$gladProvider)){
            self::__construct();
        }

        return static::$gladProvider;
    }
}
