<?php

class GladProvider {
	
	public function register()
	{
		return array(
    		'store' => 'Driver\Repository\NativeSession\Session',




    		'auth' => 'Driver\Authentication\GladAuth\Author',
    	);
  	}
}
