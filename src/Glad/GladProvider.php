<?php

namespace Glad;

class GladProvider
{
    public static $author = 'Glad\Author';

	public static function register()
	{
		return array(
    		'RepositoryInterface' 	=> 'Glad\Driver\Repository\NativeSession\Session',
            'HashInterface'         => 'Glad\Driver\Security\Hash\Hash',
            'CryptInterface'        => 'Glad\Driver\Security\Crypt\Crypt',
            'Hash'                  => 'Glad\Hash',
            'ConditionsInterface'   => 'Glad\Conditions',
            'Dispatcher'            => 'Glad\Event\Dispatcher',
            'DatabaseService'       => 'Glad\Services\DatabaseService'
    	);
  	}
}
