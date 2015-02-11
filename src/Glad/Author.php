<?php

namespace Glad;

use Glad\Driver\Repository\RepositoryInterface;
use Glad\Model\GladModelInterface;
use Glad\GladProvider;
use Glad\Constants;
use Glad\Injector;

/**
 * Auth process class
 *
 * @author Ahmet ATAY
 * @category Authentication
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
class Author {

	/**
    * Repository instance
    *
    * @var object
    */
	protected static $repository;
	
	/**
    * Author service class name
    *
    * @var string
    */
	protected static $author;

	/**
    * Constant class instance
    *
    * @var object
    */
	protected static $constants;

	/**
    * Implemented model object
    *
    * @var object
    */
	protected static $model;

	/**
    * Query builder object
    *
    * @var object
    */
	protected static $queryBuilder;

	/**
    * Identity fields
    *
    * @var array
    */
	protected static $fieldIdentity;

	/**
    * Injector class
    *
    * @var object
    */
	protected static $injector;

	/**
    * Temp user data
    *
    * @var array
    */
	protected static $tempUser;

	/**
    * Safe user data
    *
    * @var array
    */
	protected static $user;

	/**
    * User session data
    *
    * @var array
    */
	protected static $userData;

	/**
    * After register of new account 
    *
    * @var object
    */
	protected static $registerResult;


	/**
     * Class constructor
     *
     * @param object $repository
     * @param object $author
     *
     * @return void
     */ 
	public function __construct(Constants $constants, GladModelInterface $model, Injector $injector, RepositoryInterface $repository)
	{
		static::$constants = $constants;
		static::$model = $model;
		static::$injector = $injector;
		static::$repository = $repository;
		static::$author = GladProvider::$author;

		static::$userData = static::$repository->get('_gladAuth');
	}

	/**
     * New account handler method
     *
     * @param array $credentials
     * @return integer
     */ 
	public static function register(Bcrypt $crypt, array $credentials)
	{
		if(static::check() === true){

			static::checkIdentityAsParameter($credentials);
			
			if(! static::checkIdentityForRealUser($credentials)){
				static::$registerResult = false;
			}else{
				static::$tempUser = $credentials;

				$credentials['password'] = $crypt->hash($credentials['password']);
				
				static::$registerResult = static::$model->newUser($credentials);
				static::$user = $credentials;
			}
		}

		return static::getInstance();
	}

	/**
     * User login process
     *
     * @param object $bcrypt
     * @param array $user
     * @param bool $remember
     * @return bool
     */ 
	public static function login(Bcrypt $bcrypt, array $user, $remember = false)
	{
		$passField = static::$constants->authFields['password'];

		if(!isset($user[$passField]) || static::check() === true){
			return false;
		}

		$result = static::$model->getIdentity(static::getIdField($user));
		$userResult = static::resolveDbResult($result);
		
		if(count($userResult) < 1) return false;

		if(!isset($userResult[$passField])){
			return false;
		}
		
		$login = $bcrypt->verify($user[$passField], $userResult[$passField]);

		if($login === true) {
			return static::setUserRepository($userResult, $remember);
		}
		return false;
	}

	/**
     * User login process by user id
     *
     * @param int $userId
     * @return bool
     */ 
	public static function loginByUserId($userId = false)
	{
		if(static::check() === true || ! $userId) return false;

		$result = static::$model->getIdentityWithId($userId);

		if(count($result) == 1){
			
			$userResult = static::resolveDbResult($result);
			
			if(isset($userResult) && is_array($userResult)){
				return static::setUserRepository($userResult);
			}
		}
		return false;
	}

	/**
     * User logout process
     *
     * @return bool
     */ 
	public static function logout()
	{
		return static::$repository->delete('_gladAuth');
	}

	/**
     * Sets user data to repository
     *
     * @param $user array
     * @param $remember bool
     * @return bool
     */ 
	protected static function setUserRepository(array $user, $remember = false)
	{
		$userData = [
			'userData' => $user,
			'auth' => ['status' => true, 'remember' => $remember]
		];

		return static::$repository->set('_gladAuth', serialize($userData));
	}

	/**
     * Gets user data from repository
     *
     * @return array
     */ 
	public static function userData()
	{
		$data = static::$repository->get('_gladAuth');

		if($data && is_array(unserialize($data))) {
			$passField = static::$constants->authFields['password'];
			$unSerialize = unserialize($data);
			unset($unSerialize['userData'][$passField]);
			
			return $unSerialize['userData'];
		}
	}

	/**
     * Gets container class instane
     *
     * @return object
     */ 
	protected static function getInstance()
	{
		return static::$injector->inject('Glad\Glad');
	}

	/**
     * Login to after register process
     *
     * @return object
     */ 
	public static function andLogin()
	{
		if(static::status()){
			static::getInstance()->login(static::$tempUser);
			static::$tempUser = [];
		}
		return false;
	}

	/**
     * Gets login status
     *
     * @return bool
     */ 
	public static function status()
	{
		if(static::$registerResult && !is_null(static::$registerResult)){
			return true;
		}
		return false;
	}

	/**
     * Controls the given parameter
     *
     * @param array $credentials
     * @return bool|exception
     */ 
	protected static function checkIdentityAsParameter($credentials)
	{
		try {
			$fields = static::$constants->authFields;
			
			foreach($fields['identity'] as $field){
				if(! isset($credentials[$field])){
					throw new \Exception("Identity fields is missing");
				}
			}

			if( !$credentials[$fields['password']]) {
				throw new \Exception("Password field required!", 1);
			}

			return true;
		}
		catch(Exception $e){
			throw $e;
		}
	}

	/**
     * Controls the given parameter
     *
     * @param array $credentials
     * @return bool|exception
     */ 
	protected static function checkIdentityForRealUser(array $credentials)
	{
		$result = static::$model->getIdentity(static::getIdField($credentials));
		$result = static::resolveDbResult($result);
		
		return count($result) < 1;
	}

	/**
     * Check and gets authenticate fields
     *
     * @param array $credentials
     * @return array
     */ 
	protected static function getIdField(array $credentials)
	{
		$identity = static::$constants->authFields['identity'];
		$fields = [];

		if(is_array($identity)){
			foreach($identity as $id){
				if(isset($credentials[$id])){
					$fields[$id] = static::xssClean($credentials[$id]);
				}
			}
		}

		return $fields;
		
	}

	/**
     * Controls the given data from implemented model
     *
     * @param array $result
     * @return array|exception
     */ 
	protected static function resolveDbResult($result)
	{
		$exception = false;

		if(!isset($result)) return [];
		if(! is_array(reset($result))) return $result;

		foreach($result as $key => $value){
			if(is_numeric($key) && (!is_array($result[$key]) && !is_object($result[$key])) ){
				$exception = true;
			}
		}

		if(! $exception){
			return (array)reset($result);
		}

		throw new \Exception('return data incorrect');
	}

	/**
     * Clean xss data
     *
     * @param string $input
     * @return string
     */ 
	protected static function xssClean($input)
	{
		$input = strip_tags($input);
		$input = filter_var($input, FILTER_SANITIZE_STRING);

		return $input;
	}

	/**
     * Gets user logged in status
     *
     * @return bool
     */ 
	public static function check()
	{
		return static::authStatus();
	}

	/**
     * Gets user log out status
     *
     * @return bool
     */ 
	public static function guest()
	{
		return !static::authStatus();
	}

	public static function is($type)
	{

	}

	/**
     * Return user logged in status to other methods
     *
     * @return bool
     */ 
	protected static function authStatus()
	{
		$auth = static::$userData;

		if(! is_array($auth)) $auth = unserialize($auth);

		if($auth && isset($auth['auth'])) {
			return isset($auth['auth']['status']) && $auth['auth']['status'] === true;
		}

		return false;
	}
}