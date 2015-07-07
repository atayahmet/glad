Provider
======

Glad Authentication built in some class allows you to implement by redesigning the user interface . We can do this with the help of the Provider.

Below is a list of classes that you can intervene interface provided :

Name                     | Class(es)                         | Description
------------------------ |-----------------------------------| ---------------
CookerInterface          | Glad\Cooker                       | Cookie class
CryptInterface           | Glad\Driver\Security\Crypt\Crypt  | Data encryption class
HashInterface            | Glad\Driver\Security\Hash\Hash    | User password encryption
ConditionsInterface      | Glad\Driver\Security\Conditions   | User login decisive rules
SessionHandlerInterface  | Glad\Driver\Repository\~          | Session driver classes
DatabaseAdapterInterface | Model adapter                     | Database adapter classes

**Example usage:**

Let's say if you wrote Cooker that you want to replace it with another class.

First of all  create class and cooker interface  of interface to implement :

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

Then record class providers to :

```php
Glad::provider(['CookerInterface' => 'Your\Class\Path\NewCooker']);
```

or can be save later with **setup** method later with all the settings.

```php
Glad::setup([
	'provider' => [
		'CookerInterface' => 'Your\Class\Path\NewCooker'
	]
]);
```