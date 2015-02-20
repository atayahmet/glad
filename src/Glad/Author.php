<?php

namespace Glad;

use Glad\Driver\Repository\RepositoryInterface;
use Glad\UserStatusInterface;
use Glad\Model\GladModelInterface;
use Glad\GladProvider;
use Glad\Constants;
use Glad\Injector;
use Closure;

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

	protected static $rememberMe = false;
	protected static $processResult = false;

	protected static $checkActivate = false;
	protected static $checkBanned = false;
	protected static $status = false;
	protected static $toDo = [];

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
		static::resetCheckVariables();

		if(static::guest() === true){

			static::checkIdentityAsParameter($credentials);
			
			if(! static::checkIdentityForRealUser($credentials)){
				static::$registerResult = false;
			}else{
				static::$tempUser = $credentials;

				$credentials['password'] = $crypt->hash($credentials['password']);
				
				static::$registerResult = static::$model->newUser($credentials);
				static::$user = $credentials;

				if(static::$registerResult){
					static::$processResult = true;
				}
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
		static::resetCheckVariables();

		$passField = static::$constants->authFields['password'];

		if(!isset($user[$passField]) || static::check() === true){
			return static::getInstance();
		}

		$result = static::$model->getIdentity(static::getIdField($user));
		static::$user = static::resolveDbResult($result);
		
		if(count(static::$user) < 1) return static::getInstance();

		if(!isset(static::$user[$passField])){
			return static::getInstance();
		}
		
		$login = $bcrypt->verify($user[$passField], static::$user[$passField]);

		if($login === true) {
			static::$rememberMe = $remember;
			static::$processResult = true;
		}
		return static::getInstance();
	}

	public static function apply(Closure $apply, ConditionsInterface $conditions)
	{
		// $processResult: result of all processes variable
		if(static::$processResult === true){
			$apply(static::getInstance());
			
			if($conditions->apply(static::$user)){
				return static::status();
			}

			return static::$processResult = false;
		}
	}

	public function conditions(array $conditions, ConditionsInterface $cond)
	{
		$cond->add($conditions);
	}

	/**
     * User login process by user id
     *
     * @param int $userId
     * @return bool
     */ 
	public static function loginByUserId($userId = false, $remember = false)
	{
		static::resetCheckVariables();

		if(static::check() === true || ! $userId) return static::getInstance();

		$result = static::$model->getIdentityWithId($userId);

		if(count($result) == 1){
			
			static::$user = static::resolveDbResult($result);
			
			if(isset(static::$user) && is_array(static::$user)){
				static::$rememberMe = $remember;
				static::$processResult = true;
				static::status();
			}
		}
		return static::getInstance();
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

	protected static function resetCheckVariables()
	{
		static::$processResult = false;
		static::$status = false;
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
     * Gets process status and set user data
     *
     * @return bool
     */ 
	public static function status()
	{
		if(static::$processResult === true){
			static::setUserRepository(static::$user, static::$rememberMe);
			static::$userData = static::$repository->get('_gladAuth');
			static::$processResult = false;
			static::$status = true;
		}
		return static::$status;
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
		static::status();

		$auth = static::$userData;

		if(! is_array($auth)) $auth = unserialize($auth);

		if($auth && isset($auth['auth'])) {
			return isset($auth['auth']['status']) && $auth['auth']['status'] === true;
		}

		return false;
	}
}