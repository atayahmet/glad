<?php

namespace Glad;

class GladProvider {
	
	public static function register()
	{
		return array(
    		'RepositoryInterface' 	=> 'Glad\Driver\Repository\NativeSession\Session',
    		'AuthorInterface' 		=> 'Glad\Driver\Authentication\GladAuth\Author',
    		'Test'					=> 'Glad\Test'
    	);
  	}
}
