Ayarlar
======

Bu bölümde database ve repository ayarları, user tablosunda bulunması gereken bazı alanların ve beni hatırla (remember) ayarlarını yapacağız.

**Örnek yapılandırma:**
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
	]
]);
```
###Üye tablosunda olması gereken alanlar

Name     | Value
-------- | ---
idenity  | string/array
password | string

Kullanıcıları bulunduracağınız tablonuzda sütun olarak bulunması ve tanımlamanız gereken alanlar var.

Bunlardan birincisi kullanıcının kimliğinin yani bir kullanıcı adının saklanacağı alan ikincisi ise şifre alanının adının tanımlanması.

```php
'fields' => [
	'identity' => ['username','email'], 
	'password' => 'password'
]
```
###Database Adapter
Glad auth projenizde kullancağınız veritabanı seçimlerinde iki farklı yol sunmaktadır.

1. PDO driver
2. DatabaseAdapterInterface

####PDO Driver
Birinci yol PDO driver'ı kullanmak. Ayalarınızda PDO instance'ı servisler de tanımlamanız gerekmektedir.

```php
'services' => [
	'db' => new \PDO('mysql:host=localhost;dbname=exampleDb', username, password)
]
```

####DatabaseAdapterInterface
İkinci yol ise adapter yöntemi ile dilediğiniz veritabanını kullanabilirsiniz.

Servis ayarlarına tanımlayacağınız sınıfınızı DatabaseAdapterInterface arayüzüne implemente ederek methodları entegre ederek ve Glad auth'un istediği formatta return'lerini ayarlamanız gerekmektedir.

**Örnek Kullanım:**

```php
use Glad\Interfaces\DatabaseAdapterInterface;

class OrnekClass implements DatabaseAdapterInterface
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
**Arayüzün bulunduğu dizin:**
```php
Glad\Interfaces\DatabaseAdapterInterface
```

**Arayüz önizleme:**
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
**Tanımlama:**
```php
'services' => [
	'db' => new OrnekClass
]
```


###Beni Hatırla yapılandırması
Üyelik sistemlerinde sıkça kullanılan Beni Hatırla yöntemi Glad auth'ta basit bir kaç ayara tabi.

**Aşağıda bu ayarlarla ilgili tabloyu görüyorsunuz:**

Name       | Value
-----------| ---
enabled    | true/false
cookieName | string
lifetime   | timestamp integer
field      | string

#####enable:
Beni hatırla uygulamasının projenizde aktif/pasif durumunu belirlemektedir.

#####cookieName:
Glad auth bu uygulama için çerezleri kullanmaktadır. Burada çereze kendi belirleyeceğiniz ismi atayabilirsiniz. Varsayılan isim:  **_glad_auth**

#####lifetime:
Çerezin kullanılamaz hale geleceği (expire) zamanı tanımlayabilirsiniz. Değeri timestamp integer olmalıdır. 

**Örnek:**

Bu çerezin 1 yıl kullanıcının siteminde kalmasını istiyorsanız değer olarak **31536000** tanımlamanız gerekiyor.

[Timestamp integer hakkında daha fazla bilgi için tıklayınız.](http://en.wikipedia.org/wiki/Unix_time)

#####field:

Kullanıcı tablonuz da kullanabileceğimiz ve bunun ismini bilmemiz gereken bir alan olması gerekiyor. Bu alanda Beni Hatırla uygulaması içni üreteceğimiz anahtar (token)'ı barındıracağız. Örnek olarak **remember_token** olabilir.

**Tanımlama:**
```php
'remember' => [
	'enabled'   => true,
	'cookieName'=> 'glad',
	'lifetime'  => (3600*5),
	'field'	    => 'remember_token'
]
```

###Cookie Domain
Sub domain kullanıyorsanız eğer bu parametreye bunu tanımlamanız gerekmektedir. Sub domain kullanmıyorsanız boş bırakabilirsiniz.

```php
'domain' => 'sub.domain.com'
```

###Session Repository
Üyelik sistemlerimizde kullanıcı oturumlarını muhafaza etmek istediğimizde genelde PHP'nin sunmuş olduğu Session yöntemini kullanırız. Küçük projelerimizde bu fazlasıyla işimizi görüyor. Ama daha yoğun sistemlerde PHP Session performans açısından yetersiz kalıyor. 

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
-----------|
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