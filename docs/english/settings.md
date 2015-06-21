Settings
======

First do configuration;

	- Database
	- User session data storage
	- User table schema
	- Remember me

**Example configuration:**
```php
Glad::setup([
	'fields' => [
		'identity' => ['username','email'], 
		'password' => 'password'
	],
	'repository' => [
		'session'  => [
			'path'   => '/',
			'type'   => 'serialize',
			'name' 	 => 'SESSIONID',
			'prefix' => 'ses_',
			'crypt'	 => false,
			'timeout'=> 1800
		]
	],
	'provider' => [
		'SessionHandlerInterface' => 'Glad\Driver\Repository\NativeSession\Session'
	],
	'services' => [
		'db' => new \PDO('mysql:host=localhost;dbname=dbName', username, password)
	],
	'remember' => [
		'enabled'   => true,
		'cookieName'=> 'glad',
		'lifetime'  => (3600*5),
		'field'	    => 'remember_token'
	],
	'cost'   => 8,
	'secret' => '[key]'
]);
```
###User table settings

Name     | Value
-------- | ---
idenity  | string/array
password | string

Should be some fields in the user table.

The first email or username field will be used when the user's input.

This field multi than one.

**Note:**
>At the same time in both the e-mail address when registering the user you may want to log in with a user name as well. 
You want at least one or both each in him to make an entry.


**Example define:**
```php
'fields' => [
	'identity' => ['username','email'], 
	'password' => 'password'
]
```
###Database Adapter
The database is to use two different ways.

1. PDO driver
2. DatabaseAdapterInterface

####PDO Driver
The first way is to use the PDO driver. Instantiating PDO services also need to define your settings.

```php
'services' => [
	'db' => new \PDO('mysql:host=localhost;dbname=exampleDb', username, password)
]
```

####DatabaseAdapterInterface
Second way, can you use the adapter method.

**Note:**
> Some examples of existing frameworks for written about it. If you're using a framework in this package, you must browse to the absolute. [Demos](https://github.com/atayahmet/Glad-Demos)


Firstly, you should implement your class as DatabaseAdapterInterface. Then, integrate the methods which is in DatabaseAdapterInterface into database system which you want to use in the project.

Notice that 'return' of methods should be absolutely compatible with Glad Auth. Otherwise, you may come across with error. 

You see example using below.

**Example using:**

```php
use Glad\Interfaces\DatabaseAdapterInterface;

class ExampleClass implements DatabaseAdapterInterface
{
	/**
	 * Create new user
	 *
	 * @var $credentials array
	 *
	 * @return bool|int
	 */
	public function gladInsert(array $credentials)
	{
		// your way...
	}

	/**
	 * Update user
	 *
	 * @var $credentials array
	 * @var $where array
	 * @return bool
	 */
	public function gladUpdate(array $where, array $credentials)
	{
		// your way...
	}

	/**
	 * Get user identity details by identity
	 *
	 * @var $identity string
	 * @return array
	 */
	public function getIdentity($identity)
	{
		// your way...
	}

	/**
	 * Get user identity details by user id
	 *
	 * @var $userId int
	 * @return array
	 */
	public function getIdentityWithId($userId)
	{
		// your way...
	}
}
```
**Directory of Interface:**
```php
Glad\Interfaces\DatabaseAdapterInterface
```

**Interface preview:**
```php
<?php

namespace Glad\Interfaces;

/**
 * User model adapter interface
 *
 * @author Ahmet ATAY
 * @category DatabaseAdapterInterface
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
interface DatabaseAdapterInterface {

	/**
     * Data insert
     *
     * @param array $credentials
     *
     * @return bool
     */ 
	public function gladInsert(array $credentials);

	/**
     * Data update
     *
     * @param array $where
     * @param array $newData
     * @param integer $limit
     *
     * @return bool
     */ 
	public function gladUpdate(array $where, array $credentials);

	/**
     * Get the user identity
     *
     * @param array $identity
     *
     * @return array
     */
	public function getIdentity($identity);

	/**
     * Get the user identity with user id
     *
     * @param mixed $userId
     *
     * @return array
     */
	public function getIdentityWithId($userId);
}
```
**Define:**
```php
'services' => [
	'db' => new ExampleClass
]
```

###Remember Me
You can launch the Remember Me method which is irreplaceable of membership systems with some steps.

**You see the table about these steps below:**

Name       | Value               | Description
-----------| ------------------- |-------------
enabled    | true/false          | Determines the active/passive status
cookieName | string              | The cookie name that should be written session datas
lifetime   | timestamp integer   | Cookie lifetime
field      | string              | Field name that should be written key in user table


**Example Define:**
```php
'remember' => [
	'enabled'   => true,
	'cookieName'=> 'glad',
	'lifetime'  => (3600*5),
	'field'	    => 'remember_token'
]
```
###cost
User login passwords are encrypted for security. This encryption is made with **password_hash** function in php.

Being high of cost value may influence server performance. 

For more information about this subject: [password_hash](http://php.net/manual/tr/function.password-hash.php)

Default cost: 5

**Define:**
```php
'cost' => 5
```

###secret
Secret key is being used in many fields for security in encryption processes. You should create your own Secret Key.

**Define:**
```php
'secret' => '_|()44?'
```

You can find the encryption architecture below table:

Name       | Value
-----------| ---
MCRYPT_RIJNDAEL_128    | Algorithm
MCRYPT_MODE_ECB | Encryption mode

Read more information: [PHP Mcrypt](http://php.net/manual/en/book.mcrypt.php)


###Cookie Domain
Sub domain kullanıyorsanız eğer bu parametreye bunu tanımlamanız gerekmektedir. Kullanmıyorsanız boş bırakabilirsiniz.

```php
'domain' => 'sub.domain.com'
```

###Session Repository
PHP projelerde üyelik sistemleri genelde native session kullanmaktadır. Orta halli projelerde bu yöntem iş görebilmektedir. 

Fakat daha yoğun sistemlerde native session yetersiz kalabilir. Bu gibi durumlarla karşılaşıldığında tercih edilen bazı yöntemlere örnek olarak şunlar verilebilir:

- Memcache
- Redis
- Mongo
- Database

Glad Auth içinde default olarak gelen yöntemler:
- PHP Native Session
- Memcache

Fazlası elbette mümkün, [Glad Provider](http://glad.readthedocs.org/en/latest/turkish/provider/) yardımıyla dilediğiniz ön bellekleme yöntemlerini kullanabilirsiniz.

Sırasıyla default yöntemleri inceleyelim:

##PHP Session

**Parametreler:**

Name       | Value               | Description
-----------| ------------------- | --------------
path       | string              | Oturum bilgilerinin depolanacağı dizin
type       | string           | Veri serileştirme türü (default: **serialize**) 
name       | string              | Çerez adı (default: **SESSIONID**)
timeout    | timestamp integer   | Oturum yaşam süresi (default: **30 dk.**)
crypt      | boolean             | Verilerin şifrelenmesi (default: **false**)
prefix     | string              | Oturum dosyası ön adı (default: **ses_**)


**Provider:**

Interface                         | Class
----------------------------------| --------------------------------------------
SessionHandlerInterface       | Glad\Driver\Repository\NativeSession\Session


**Serileştirme türleri:**

Name       | Description
-----------| -------------
json       | encode/decode
serialize  | Php Serialize


Örnek tanımlama:

```php
'repository' => [
	'session'  => [
		'path' 	  => '/path/storage',
		'type'	  => 'json',
		'name'	  => 'PHPSESSID',
		'timeout' => 1800,
		'crypt'	  => false,
		'prefix'  => 'ses_'
	]
],
'provider' => ['SessionHandlerInterface' => 'Glad\Driver\Repository\NativeSession\Session']
```

##Memcache

**Parametreler:**

Name       | Value              | Description
-----------| ------------------ | --------------------
host       | string             | Memcache sunucu ip (default: **127.0.0.1**)
port       | integer            | Memcache sunucu port (default: **11211**)
name       | string             | Çerez adı (default: **SESSIONID**)
timeout    | timestamp integer  | Oturum yaşam süresi (default: **30 dk.**)
crypt      | boolean            | Verilerin şifrelenmesi (default: **false**)
prefix     | string             | Oturum dosyası ön adı (default: **ses_**)

**Provider:**

Interface                         | Class
----------------------------------| --------------------------------------------
SessionHandlerInterface       | Glad\Driver\Repository\Memcache\Memcache

**Örnek tanımlama:**

```php
'repository' => [
	'memcache'  => [
		'host'	  => '127.0.0.1',
		'port'	  => 11211,
		'timeout' => 1800,
		'prefix'  => 'ses_',
		'crypt'	  => false,
		'name'	  => 'PHPSESID'
	]
],
'provider' => ['SessionHandlerInterface' => 'Glad\Driver\Repository\Memcache\Memcache']
```

##Memcached

**Parametreler:**

Name       | Value               | Description
-----------| ------------------- | ------------------
host       | string             | Memcache sunucu ip (default: **127.0.0.1**)
port       | integer            | Memcache sunucu port (default: **11211**)
name       | string             | Çerez adı (default: **SESSIONID**)
timeout    | timestamp integer  | Oturum yaşam süresi (default: **30 dk.**)
crypt      | boolean            | Verilerin şifrelenmesi (default: **false**)
prefix     | string             | Oturum dosyası ön adı (default: **ses_**)

**Provider:**

Interface                         | Class
----------------------------------| --------------------------------------------
SessionHandlerInterface       | Glad\Driver\Repository\Memcached\Memcached

**Örnek tanımlama:**

```php
'repository' => [
	'memcached'  => [
		'host'	  => '127.0.0.1',
		'port'	  => 11211,
		'timeout' => 1800,
		'prefix'  => 'ses_',
		'crypt'	  => false,
		'name'	  => 'PHPSESID'
	]
],
'provider' => ['SessionHandlerInterface' => 'Glad\Driver\Repository\Memcached\Memcached']
```