Ayarlar
======

İlk yapacağımız yapılandırmalar;

	- Veritabanı
	- Kullanıcı oturum verilerini depolama
	- Kullanıcı tablosu
	- Beni hatırla (Remember Me)

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
	],
	'cost'   => 8,
	'secret' => '[key]'
]);
```
###Üye tablosu ayarları

Name     | Value
-------- | ---
idenity  | string/array
password | string

Kullanıcı tablosunda mutlaka eklemeniz gereken bazı alanlar var.

Birincisi kullanıcının giriş yaparken kullanacağı email yada username alanı. 
Bu alan birden fazla olabilir.

**Not:**
>Kullanıcının giriş yaparken aynı anda hem eposta adresi hemde bir kullanıcı adı ile giriş yapmasını isteyebilirsiniz. Yada her her ikisinden en az biriyle giriş yapmasınıda isteyebilirsiniz.


**Örnek tanımlama:**
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
İkinci yol ise adapter yöntemi ile dilediğiniz veritabanını kullanma imkanınız bulunuyor.

**Not:**
> Bazı framework'ler için bununla ilgili yazılmış örnekler mevcut. Şayet bir framework içinde kullanıyorsanız bu paketi mutlaka göz atmalısınız. [Örnekler için tıklayınız](https://github.com/atayahmet/Glad-Demos)


İlk olarak yapmanız gereken sınıfınızı DatabaseAdapterInterface implemente etmek. Sonrasında DatabaseAdapterInterface'de sunulan methodları projenizde kullanmak istediğiniz veritabanı sistemine entegre ediniz. 

Dikkat etmeniz gereken konu methodların return'leri mutlaka Glad Auth ile uyumlu olmalıdır. Aksi takdirde hatalar ile karşılaşabilirsiniz.

Aşağıda örnek bir kullanım bulunuyor.

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


###Beni Hatırla (Remember Me)
Üyelik sistemlerinin vazgeçilmezi Beni Hatırla yöntemini bir kaç ayar ile hızlıca faaliyete sokabilirsiniz.

**Aşağıda bu ayarlarla ilgili tabloyu görüyorsunuz:**

Name       | Value               | Description
-----------| ------------------- |-------------
enabled    | true/false          | Aktif/Pasif durumunu belirler
cookieName | string              | Oturum verilerinin yazılacağı çerez adı
lifetime   | timestamp integer   | Çerez yaşam süresi
field      | string              | Kullanıcı tablosunda anahtarın yazılacağı alan adı


**Örnek tanımlama:**
```php
'remember' => [
	'enabled'   => true,
	'cookieName'=> 'glad',
	'lifetime'  => (3600*5),
	'field'	    => 'remember_token'
]
```
###cost
Kullanıcı giriş parolaları güvenlik adına şifrelenmektedir. Bu şifreleme php deki **password_hash** fonksiyonu ile yapılmaktadır. 

Cost değerinin yüksek olması sunucu performansını ciddi anlamda etkileyebilir. Bu konu hakkında detaylı bilgiye şu linkten ulabilirsiniz: [password_hash](http://php.net/manual/tr/function.password-hash.php)

Varsayılan cost: 5

**Tanımlama:**
```php
'cost' => 5
```

###secret
Secret key güvenlik adına şifreleme işlemleri için bir çok alanda kullanılmaktadır. Burada mutlaka kendi Secret key'inizi oluşturmalısınız.

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