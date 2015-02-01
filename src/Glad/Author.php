<?php

namespace Glad;

use Glad\Driver\Repository\RepositoryInterface;
use Glad\AuthorInterface;
use glad\GladModelInterface;
use Closure;

class Author implements AuthorInterface {

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

	/**
    * Class constructor
    *
    * @param object $repository
    * @param object $author
    *
    * @return void
    */ 
	public function __construct(RepositoryInterface $repository)
	{
		static::$repository = $repository;

		//static::$author = $author;
	}

	public static function register(array $credentials, GladModelInterface $model)
	{
		$model->newUser($credentials);
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