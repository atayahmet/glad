<?php

namespace Glad;

use Glad\Driver\Repository\RepositoryInterface;
use Glad\AuthorInterface;
use Glad\GladModelInterface;
use Glad\Constants;
use Closure;

class Author extends Glad implements AuthorInterface {

	/**
    * Repository instance
    *
    * @var object
    */
	protected static $repository;
	
	/**
    * Author instance
    *
    * @var object
    */
	protected static $author;

	protected static $constants;

	protected static $model;

	/**
    * Class constructor
    *
    * @param object $repository
    * @param object $author
    *
    * @return void
    */ 
	public function __construct(Constants $constants, GladModelInterface $model)
	{
		static::$constants = $constants;

		static::$model = $model;
	}

	/**
    * Class constructor
    *
    * @param object $repository
    * @param object $author
    *
    * @return void
    */ 
	public static function register(array $credentials)
	{
		static::checkIdentityAsParameter($credentials);

		
		$createResult = static::$model->newUser($credentials);
	}

	protected static function checkIdentityAsParameter($credentials)
	{
		try {
			$fields = static::$constants->authFields;

			if(! $credentials[$fields['identity']] || !$credentials[$fields['password']]) {
				throw new \Exception("Identity and password fields required!", 1);
			}

			return true;
		}
		catch(Exception $e){
			exit($e->getMessage());
		}
	}

	public function login(array $user, $remember, RepositoryInterface $repository)
	{
		return $repository;
	}

	public function check()
	{
		
	}

	public function guest()
	{

	}

	public function is($type)
	{

	}

}