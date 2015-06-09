<?php

namespace Glad;

/**
 * Providers
 *
 * @author Ahmet ATAY
 * @category GladProvider
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class GladProvider
{
    public static $author = 'Glad\Author';

    protected static $providers = [
        'SessionHandlerInterface'   => 'Glad\Driver\Repository\NativeSession\Session',
        'HashInterface'             => 'Glad\Driver\Security\Hash\Hash',
        'CryptInterface'            => 'Glad\Driver\Security\Crypt\Crypt',
        'CookerInterface'           => 'Glad\Cooker',
        'ConditionsInterface'       => 'Glad\Conditions',
        'Hash'                      => 'Glad\Hash',
        'Dispatcher'                => 'Glad\Event\Dispatcher',
        'DatabaseServiceInterface'           => 'Glad\Services\DatabaseService'
    ];

    /**
     * Get the one provider or all provider
     *
     * @param $name string
     *
     * @return array|string|null
     */
	public static function get($name = false)
	{
        if($name) {
            return isset(static::$providers[$name]) ? static::$providers[$name] : null;
        }
		return static::$providers;
  	}

    /**
     * Set the provider
     *
     * @param $_providers array
     *
     * @return void
     */
    public static function set(array $_providers)
    {
        static::$providers = array_merge(static::$providers, $_providers);
    }
}
