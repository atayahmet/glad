<?php

namespace Glad;

class GladProvider
{
    public static $author = 'Glad\Author';

    protected static $providers = [
        'SessionHandlerInterface'   => 'Glad\Driver\Repository\NativeSession\Session',
        'HashInterface'         => 'Glad\Driver\Security\Hash\Hash',
        'CryptInterface'        => 'Glad\Driver\Security\Crypt\Crypt',
        'CookerInterface'       => 'Glad\Cooker',
        'ConditionsInterface'   => 'Glad\Conditions',
        'Hash'                  => 'Glad\Hash',
        'Dispatcher'            => 'Glad\Event\Dispatcher',
        'DatabaseService'       => 'Glad\Services\DatabaseService'
    ];

	public static function get()
	{
		return static::$providers;
  	}

    public static function set(array $_providers)
    {
        static::$providers = array_merge(static::$providers, $_providers);
    }
}
