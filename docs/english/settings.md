Ayarlar
======

Bu bölümde database ve repository ayarları, user tablosunda bulunması gereken bazı alanların ve beni hatırla (remember) ayarlarını yapacağız.

Bu bölümde veritabanı, kullanıcı oturum bilgileri depolanması, beni hatırla (remember me) kavramı ile ilgili ayarları yapacağız.


Settings
In this part we will make the setting of database and repository, and the settings of Remember me.

**Example configrations:**
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
###Üye tablosunda olması gereken alanlar
### Required fields in user table 
Name     | Value
-------- | ---
idenity  | string/array
password | string

Kullanıcıları bulunduracağınız tablonuzda sütun olarak bulunması ve tanımlamanız gereken alanlar var.
In the user table there is columns should be define.

İlk sütun kullanıcı adı yada email alanı olmalı, opsiyonel olarak bu alanlar birden fazla olabilir.
First column require username or email, optionally can be more than one.

İkinci alan ise kullanıcı şifresinin saklanacağı alan adı.
Second column used to save the user password.


```php
'fields' => [
	'identity' => ['username','email'], 
	'password' => 'password'
]
```
###Database Adapter
Glad auth veritabanı seçimlerinizde iki farklı yol sunmaktadır.
Glad auth gives you two different ways in the database that you are going to use in your project.

1. PDO driver
2. DatabaseAdapterInterface

####PDO Driver
Birinci yol PDO driver'ı kullanmak. Ayarlarınızda PDO instance'ı servisler de tanımlamanız gerekmektedir.
First way using PDO driver. In the settings you have to define PDO instance.

```php
'services' => [
	'db' => new \PDO('mysql:host=localhost;dbname=exampleDb', username, password)
]
```

####DatabaseAdapterInterface
İkinci yol ise adapter yöntemi ile dilediğiniz veritabanını kullanabilirsiniz.
Second way by using adapter method you can use any database.

DatabaseAdapterInterface arayüzü ile implemente edeceğiniz sınıfınızı servis ayarlarında tanımlamalısınız.
You have to define the implemented class of interface "DatabaseAdapterInterface" in service settings.

Implement ettiğiniz sınıfın methodları Glad Auth'un istediği formatta olmalıdır.
The implemented class methods should be in the same form Glad Auth required

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
**Directory of interface:**
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


###Remember Me configrations
Üyelik sistemlerinde sıkça kullanılan Beni Hatırla yöntemi Glad auth'ta basit bir kaç ayara tabi.
Frequently used Remember Me method in the membership systems is few simple settings in Glad Auth.

**Aşağıda bu ayarlarla ilgili tabloyu görüyorsunuz:**
**Below you can see the settings table:**

Name       | Value
-----------| ---
enabled    | true/false
cookieName | string
lifetime   | timestamp integer
field      | string

#####enable:
Beni hatırla yönteminin projenizde aktif/pasif durumunu belirlemektedir.
Remember Me method can be active and deactive status in your project.

#####cookieName:
Glad auth bu yöntem için çerezleri kullanmaktadır. Burada çereze kendi belirleyeceğiniz ismi atayabilirsiniz. Varsayılan isim:  **_glad_auth**

Glad Auth use cookies for this method. You can name the cookies as you want.

#####lifetime:
Çerezin kullanılamaz hale geleceği (expire) zamanı tanımlayabilirsiniz. Değeri timestamp integer olmalıdır. 
You can define the lifetime expire of the cookie. The value should be timestamp integer

**Example:**

Çerezin 1 yıl kullanıcının sisteminde kalmasını istersek değer olarak **31536000** tanımlamanız gerekiyor.
For a year value should be is 31536000
If you would like to extend to cookie lifetime to one year you should add this value: **31536000**

[Read more Timestamp](http://en.wikipedia.org/wiki/Unix_time)

#####field:
Kullanıcı tablosunda token için bir alan oluşturmanız gerekiyor.
In the user table you should create area for token.

Example area name can be **remember_token**.
Örnek olarak alan adı **remember_token** olabilir.

**Define:**
```php
'remember' => [
	'enabled'   => true,
	'cookieName'=> 'glad',
	'lifetime'  => (3600*5),
	'field'	    => 'remember_token'
]
```
###cost
Kullanıcı şifreleri Glad Auth'da password_hash ile yeniden şifrelenmektedir. Cost (şifreleme maliyeti) değeri sunucunuzun performansına göre optimize edilmelidir. Varsayılan cost: 8

User passwords hashed again with method name password_hash in Glad Auth. The cost value should be minimized according your server performance.

**Define:**
```php
'cost' => 8
```

About read more : [password_hash](http://php.net/manual/tr/function.password-hash.php)

###secret
Kullanıcılar Beni hatırla özelliğini kullanmak istediklerinde bazı verileri php'nin mcrypt_encrypt/mcrypt_decrypt fonksiyonları ile şifrelemektedir. Bunu yaparkende sizin belirleyeceğiniz yada varsayılan olarak belirlenen secret key'i kullanabilirsiniz. Kendi secret key'inizi mutlaka oluşturmalısınız.

**Tanımlama:**
```php
'secret' => '_|()44?'
```

Aşağıdaki tabloda şifreleme mimarisini bulabilirsiniz:

Name       | Value
-----------| ---
MCRYPT_RIJNDAEL_128    | Algoritma
MCRYPT_MODE_ECB | Şifreleme modu

Detalı bilgi için: [PHP Mcrypt](http://php.net/manual/en/book.mcrypt.php)


###Cookie Domain
Sub domain kullanıyorsanız eğer bu parametreye bunu tanımlamanız gerekmektedir. Sub domain kullanmıyorsanız boş bırakabilirsiniz.

```php
'domain' => 'sub.domain.com'
```

###Session Repository
Üyelik sistemlerimizde kullanıcı oturumlarını muhafaza etmek istediğimizde genelde PHP'nin sunmuş olduğu Session yöntemini kullanırız. Küçük projelerimizde bu fazlasıyla işimizi görüyor fakat daha yoğun sistemlerde PHP Session performans açısından yetersiz kalıyor. 

Bu durumlarda bize performans açısından avantaj kazandıracak farklı uygulamalar tercih edilir. Bunlara örnek olarak Memcache, Memcached, Redis, Mongo yada Database destekli session yöntemleri verilebilir.

Glad Authentication'da PHP Session, Memcache, Memcached dahili olarak mevcut bulunuyor. Bunların dışında Glad Provider aracılığıyla istediğiniz farklı yöntemleri entegre edebilirsiniz.

####PHP Session

**Parametreler:**

Name       | Value
-----------| ---
path       | string
type       | json
name       | string
timeout    | timestamp integer
crypt      | boolean
prefix     | string


**Provider:**

Interface                         | Class
----------------------------------| --------------------------------------------
SessionHandlerInterface       | Glad\Driver\Repository\NativeSession\Session

#####path:
PHP Session kullanıcı verilerini belirleyeceğiniz dizinde depolayacaktır. Bu dizini **path** parametresi altında tanımlamanız gerekiyor.

#####type:
Kullanıcı verilerini hangi türde serileştirmesi gerektiğini tanımlayabilirsiniz. Varsayılan: **serialize**

Serileştirme türleri:

Name       | Description
-----------| -------------
json       | encode/decode
serialize  | Php Serialize


#####name:
Kullanıcı tarafında kullanacağımız çerez (cookie)'nin adını belirler. Varsayılan çerez adı: **SESSIONID**

#####timeout:
Kullanıcının sistemde ne kadar süre her hangi bir aktivitede bulunmadığında oturumunun kapanacağını belirler. Saniye cinsinden belirtilmelidir.

Örnek:
Kullanıcının 30 dakika içinde her hangi bir işlem yapmadığında oturumunun kapatılmasını istiyorsak.

```php
'timeout' => 1800
```

#####crypt:
Oturum verilerinin şifrelenmesini istiyorsanız bu değeri **true** olarak tanımlamanız gerekiyor. Varsayılan: **false**

#####prefix:
Oturum hash değerinin ön eki'dir. Varsayılan: **ses_**

Örnek:
```php
ses_2490537e432c2d489381934905cedf9aa7ccda0e
```

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

####Memcache

**Parametreler:**

Name       | Value
-----------| ---
host       | string
port       | integer
name       | string
timeout    | timestamp integer
crypt      | boolean
prefix     | string

**Provider:**

Interface                         | Class
----------------------------------| --------------------------------------------
SessionHandlerInterface       | Glad\Driver\Repository\Memcache\Memcache

#####host:
Kullanacağınız Memcached sunucunun ip adresi yada socket bağlantı yapacak iseniz socket path'ini girmeniz gerekmektedir.

Detaylı bilgi: [http://php.net/manual/en/memcache.connect.php](http://php.net/manual/en/memcache.connect.php)
 
#####port:
Memcached sunucu portu. Varsayılan port: **11211**


#####name:
Kullanıcı tarafında kullanacağımız çerez (cookie)'nin adını belirler. Varsayılan çerez adı: **SESSIONID**

#####timeout:
Kullanıcının sistemde ne kadar süre her hangi bir aktivitede bulunmadığında oturumunun kapanacağını belirler. Saniye cinsinden belirtilmelidir. 
Varsayılan süre: 1800 sec. (30 dakika)

Örnek:
Kullanıcının 30 dakika içinde her hangi bir işlem yapmadığında oturumunun kapatılmasını istiyorsak.

```php
'timeout' => 1800
```

#####crypt:
Oturum verilerinin şifrelenmesini istiyorsanız bu değeri **true** olarak tanımlamanız gerekiyor. Varsayılan: **false**

#####prefix:
Oturum hash değerinin ön eki'dir. Varsayılan: **ses_**

Örnek:
```php
ses_2490537e432c2d489381934905cedf9aa7ccda0e
```

Örnek tanımlama:

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

####Memcached

**Parametreler:**

Name       | Value
-----------| ---
host       | string
port       | integer
name       | string
timeout    | timestamp integer
crypt      | boolean
prefix     | string

**Provider:**

Interface                         | Class
----------------------------------| --------------------------------------------
SessionHandlerInterface       | Glad\Driver\Repository\Memcached\Memcached

#####host:
Kullanacağınız Memcached sunucunun ip adresi yada socket bağlantı yapacak iseniz socket path'ini girmeniz gerekmektedir.

Detaylı bilgi: [http://php.net/manual/en/memcached.addserver.php](http://php.net/manual/en/memcached.addserver.php)
 
#####port:
Memcached sunucu portu. Varsayılan port: **11211**


#####name:
Kullanıcı tarafında kullanacağımız çerez (cookie)'nin adını belirler. Varsayılan çerez adı: **SESSIONID**

#####timeout:
Kullanıcının sistemde ne kadar süre her hangi bir aktivitede bulunmadığında oturumunun kapanacağını belirler. Saniye cinsinden belirtilmelidir.
Varsayılan süre: 1800 sec. (30 dakika)

Örnek:
Kullanıcının 30 dakika içinde her hangi bir işlem yapmadığında oturumunun kapatılmasını istiyorsak.

```php
'timeout' => 1800
```

#####crypt:
Oturum verilerinin şifrelenmesini istiyorsanız bu değeri **true** olarak tanımlamanız gerekiyor. Varsayılan: **false**

#####prefix:
Oturum hash değerinin ön eki'dir. Varsayılan: **ses_**

Örnek:
```php
ses_2490537e432c2d489381934905cedf9aa7ccda0e
```

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