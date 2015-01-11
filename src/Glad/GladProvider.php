<?php

namespace Glad;

class GladProvider {
	
	public static function register()
	{
		return array(
    		'store' => 'Driver\Repository\NativeSession\Session',




    		'auth' => 'Driver\Authentication\GladAuth\Author',
    	);
  	}
}
