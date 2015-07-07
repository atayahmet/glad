Method References
=======

###login()

Registered users information and membership information  that are maintained by us comparison by the system and if the information match the process is successful system starts the user sesision.

The two parameter process:

Name     | Type | Description
-------- | ------|------------
identity  | array | It contains the user’s login information. It must be sent in sequence.
remember | bool  | Remeber me specification operate. The parameter is **false**


return  | Description
--------|------------
instance| Return instance of Glad container class

**Example:**

```php
Glad::login(['username' => 'exampleuser', 'password' => '1234'], true);
```

To check the result of process above:

```php
if(Glad::status() === true) {
	// do something...
}
```

> Note: Make sure that in the startup the username and the e-mail that are written should be matched  in both setup method and **fields** area. You can use both or at least one as  login condition up on your choice.

###loginByUserId()

As you can understand from the method name with this method memeber can login in by Member ID number. In some cases user may want to  login without enquiring member information .

Parameters

Name     | Type | Description
-------- | ------|------------
User id  | mixed | The number of  memebership in user table ( User ID)

return  | Description
--------| -------------
instance| Return instance of Glad container class

**Example**
```php
Glad::loginByUserId(1);
```

or;

```php
Glad::loginByUserId('507f191e810c19729de860ea');
```

To check the result of process above:

```php
if(Glad::status() === true) {
	// do something...
}
```

or;
```php
if(Glad::loginByUserId(1)->status() === true) {
	// do something...
}
```

###andLogin()

Usually when new member registered to the system it's done by ( register method) the user is only registered.If he want to also login to the system you should use the **andLogin** method. 


return  | Description
--------| -----------
instance| Return instance of Glad container class

**Example:**
```php
Glad::register([])->andLogin();
```
or;
```php
Glad::register([]);

Glad::andLogin();
```

> Note:  andLogin method doesn’t use any parameter.


**Example**
```php
Glad::register([
	'email'	    => 'email@example.com',
	'password'  => '1234',
	'firstname' => 'Firstname',
	'lastname'  => 'Lastname'
])->andLogin();
```

To check the result of the process:
```php
if(Glad::status() === true) {
	// do something...
}
```

###logout()

Close’s the user session completely. If the user use Remember me option session end directly. User requires to login again. 

return  | Description
--------|------------
bool    | true/false

**Example**
```php
Glad::logout();
```

###register()

Process user registration. 

**Parameters**

Name         | Type | Description
------------ | ------|------------
credentials  | array | Required information for registration

return  | Description
--------| -----------
instance| Return instance of Glad container class

**Example:**
```php
Glad::register([
	'email'	    => 'email@example.com',
	'password'  => '1234',
	'firstname' => 'Firstname',
	'lastname'  => 'Lastname'
]);
```

To check process result:
```php
if(Glad::status() === true) {
	// do something...
}
```

###change()

When we want to change user information that valued in the system includes the password can be done  by change method. 

return  | Description
--------| -----------
instance| Return instance of Glad container class

**Parameters**

Name         | Type | Description
------------ | ------|------------
credentials  | array | New user information

```php
Glad::change([
	'email'	    => 'email@example.com',
	'password'  => '1234',
	'firstname' => 'Firstname',
	'lastname'  => 'Lastname'
]);
```

To check process result:
```php
if(Glad::status() === true) {
	// do something...
}
```

> Note: New information should be mentioned before in the registration process. 

###apply()

We can say that the detailed cross- checks essential system of the membership is very important. Glad author lets you manage after the behavior controls by using apply method.


**apply** method take **Closure**  method in parameter.


Name         | Type   | Description
------------ | -------|------------
Closure      | object | Contains controls

return  | Description
--------| -----------
instance| 
Return instance of Glad container class

**Example**

```php
Glad::login(['username' => 'exampleuser', 'password' => '1234'], true)
	->apply(function(Glad $glad) {
		$glad->conditions(['banned' => 0, 'activate' => 1]);
			
		$glad->event('banned', function() {
				exit('you are banned');
			});

		$glad->event('activate', function() {
				exit('your account has activated');
			});
		});
```

Let us examine the above code block.

First we have run the **login** method and then we run the **apply** method by using chain method.

Here you can see that we have done the Closure method,and this method injects instance to Glad class.

###conditions()

In membership system there are some classification should be done. For example you can apply filter to users that are not activated in the system.

```php
$glad->conditions(['banned' => 0, 'activate' => 1]);
```

Example by **banned** and **activate** fields you can control them.


> Note: This fields should be available in your members table.


###event()

Inside the  **apply** method according to the **conditions** methods result it flows the process. 


```php
$glad->conditions(['banned' => 0, 'activate' => 0]);
```

After user login in to the system in the second stage we are making account statu checkups.

If the **banned** section in the account is **0**  this event will work.

```php
$glad->event('banned', function() {
	exit('you are banned');
});
```

If the **activate**  section in the account is **0**  this event will work:

```php
$glad->event('activate', function() {
	exit('you account has not activated');
});
```

###status()

This methods can be use in members and new registered users

**Example**
```php
Glad::login(['email' => 'example@email.com', 'password' => '1234'], true);
```

This is the process use to status method:

```php
if(Glad::status() === true) {
	// do something...
}
```

This is the process use to status method:

Name         | Description
------------ | -------
login        | After user login
loginByUserId| After user number login
andLogin     | After user registry login process
register     | After register process
change       | After user change account information process

###check()

It checks the user session in the system.If the session is active the result turns to **true** and if not turns to **false**.

**Example**

```php
if(Glad::check() === true) {
	// do something...
}
```

###guest()

It checks the user session in the system.If the session is active the result turns to **true** and if not turns to **false**.


**Example**
```php
if(Glad::guest() === true) {
	// do something...
}
```

###is()

With this method you can use check and guest methods.

**Example 1**
```php
if(Glad::is('check') === true) {
	// do something...
}
```
**Example 2**
```php
if(Glad::is('guest') === true) {
	// do something...
}
```

###toArray()

Turns the data of  user session to **array**.

Example:
```php
Glad::toArray();
```

###toObject()

Turns the data of  user session to **stdObject**.

Example:
```php
Glad::toObject();
```

###toJson()

Turns the data of  user session to **JSON**.

Example:
```php
Glad::toJson();
```

###toXml()

Turns the data of  user session to **XML**.

Example:
```php
Glad::toXml();
```
