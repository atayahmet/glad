Quickstart
====

```php
use Glad\Glad;
```

**User Login:**
```php
Glad::login(['email' => 'exampleuser', 'password' => '1234'], true);
```

**Process validation:**
```php
if(Glad::status() === true){
	// do something...
}
```
You can see up a simple member login example.

It is possible to make member login process more detailed. You can find details regarding this subject in detailed use section.

**New user register process:**
```php
Glad::register([
	'email'	    => 'email@example.com',
	'password'  => '1234',
	'firstname' => 'Firstname',
	'lastname'  => 'Lastname'
]);
```

**Process validation:**

```php
if(Glad::status() === true){
	// do something...
}
```

Above we use only email as username identification, upon your request you can add also username. you can find explanation regarding this subject in settings section. 

**Changing Username info:**

```php
Glad::change([
	'firstname' => 'New firstname',
	'lastname'  => 'New lastname'
]);
```

Changing User password can be done by using **change** method.
```php
Glad::change(['password'  => '123412']);
```