<?php

namespace Glad;

class GladProvider {
	
    public static $author = 'Glad\Author';

    public static $sender = array(
            'environment' => 'test',
            'send' => true,
        );

	public static function register()
	{
		return array(
    		'RepositoryInterface' 	=> 'Glad\Driver\Repository\NativeSession\Session',
    		'AuthorInterface' 		=> 'Glad\Driver\Authentication\GladAuth\Author'
    	);
  	}
}
