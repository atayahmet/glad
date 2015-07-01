Provider
======

Glad Authentication dahili bir kaç sınıfı user interface ile implemente ederek yeniden tasarlamanıza imkan veriyor. Bunu da Provider yardımıyla yapabiliyoruz.

Aşağıda müdahale edebileceğiniz sınıfların arayüz listesi bulunmakltadır:

Name                     | Class(es)                         | Description
------------------------ |-----------------------------------| ---------------
CookerInterface          | Glad\Cooker                       | Cookie sınıfı
CryptInterface           | Glad\Driver\Security\Crypt\Crypt  | Veri şifreleme sınıfı
HashInterface            | Glad\Driver\Security\Hash\Hash    | Kullanıcı şifresi şifreleme
ConditionsInterface      | Glad\Driver\Security\Conditions   | Kullanıcı girişi kural belirleyici
SessionHandlerInterface  | Glad\Driver\Repository\~          | Session driver sınıfları
DatabaseAdapterInterface | Model adapter                     | Database adapter sınıfı

**Örnek kullanım:**

Diyelim Cooker sınıfını kendinizin yazmış olduğu başka bir sınıf ile değiştirmek istiyorsunuz.

Öncelikle sınıfımı oluşturuyorum ve CookerInterface arayüzüne implemente ediyorum:

```php

use Glad\Interfaces\CookerInterface;

class NewCooker implements CookerInterface {
	
	public function set($name = false, $value = false, $lifeTime = '', $path = '/', $domain = '', $secure = false, $httpOnly = false)
	{
		// your methods...
	}

	public function remove($name)
	{
		// your methods...
	}

	public function get($name)
	{
		// your methods...
	}

	public function has($name)
	{
		// your methods...
	}

}
```

Daha sonra sınıfımı provider'a kayıt ediyorum:

```php
Glad::provider(['CookerInterface' => 'Your\Class\Path\NewCooker']);
```

ya da **setup"" metodu ile tüm ayarların kaydedilmesi esnasında yapabilirsiniz:

```php
Glad::setup([
	'provider' => [
		'CookerInterface' => 'Your\Class\Path\NewCooker'
	]
]);
```