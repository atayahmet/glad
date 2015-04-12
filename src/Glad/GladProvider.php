<?php

namespace Glad;

class GladProvider
{
    public static $author = 'Glad\Author';

	public static function register()
	{
		return array(
    		'RepositoryInterface' 	=> 'Glad\Driver\Repository\NativeSession\Session',
            'Bcrypt'                => 'Glad\Bcrypt',
            'ConditionsInterface'   => 'Glad\Conditions',
            'Dispatcher'            => 'Glad\Event\Dispatcher',
            'DatabaseService'       => 'Glad\Services\DatabaseService'
    	);
  	}
}
