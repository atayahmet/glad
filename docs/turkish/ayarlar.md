Ayarlar
======

Bu bölümde database ve repository ayarları, user tablosunda bulunması gereken bazı alanların ve beni hatırla (remember) ayarlarını yapacağız.

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
###Servisler
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
	public function insert(array $credentials)
	{
		// your own methods...
	}
	
	public function update(array $where, array $newData)
	{
		// your own methods...
	}
	public function getIdentity($user)
	{
		// your own methods...
	}
	public function getIdentityWithId($user)
	{
		// your own methods...
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

interface DatabaseAdapterInterface {
	
	/**
     * For new data input
     *
     * @param array $credentials
     * @return bool|int
     */ 
	public function insert(array $credentials);
	
	/**
     * To update procedures
     *
     * @param array $credentials
     * @return bool
     */ 
	public function update(array $where, array $newData);

	/**
     * Receives the user information with the user name
     *
     * @param array $user
     * @return array
     */ 
	public function getIdentity($user);

	/**
     * Receives the user information with the user id
     *
     * @param array $user
     * @return array
     */ 
	public function getIdentityWithId($user);
}
```
**Tanımlama:**
```php
'services' => [
	'db' => new OrnekClass
]
```


####Beni Hatırla (Remember Me) yapılandırması
Üyelik sistemlerinde sıkça kullanılan Beni Hatırla yöntemi Glad auth'ta basit bir kaç ayara tabi.

**Aşağıda bu ayarlarla ilgili tabloyu görüyorsunuz:**

Name       | Value
-----------| ---
enabled    | true/false
cookieName | string
lifetime   | timestamp integer
field      | string