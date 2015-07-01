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

Üyelik sistemlerinde detaylı çapraz kontroller olmazsa olmazlardan diyebiliriz. Glad auth burada sizlere apply metoduyla detaylı kontroller ve bu kontoller sonrası davranışları yönetmenizi sağlıyor. 

**apply** method take **Closure** method in parameter.

Name         | Type   | Description
------------ | -------|------------
Closure      | object | Contains controls

return  | Description
--------| -----------
instance| 
Return instance of Glad container class

**Örnek**

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

Yukarıdaki kod bloğunu sırasıyla inceleyelim.

İlk olarak **login** metodumuzu çalıştırdık ve hemen sonrasında **apply** metodunu chain yöntemiyle çalıştırdık.

Burada bir Closure metodunu kurguladığımızı görüyoruz. Ve bu metoda Glad class'sının instance'ı injekt ediliyor.

###conditions()

Üyelik sistemlerin de kullanıcıların durumlarıyla ilgili bir takım sınıflandırmalar yapılmaktadır. Mesela üyeliğini henüz aktifleştirmemiş olan kullanıcılar için burada bir filtre uygulayabilirsiniz. 

```php
$glad->conditions(['banned' => 0, 'activate' => 1]);
```
Örnekte **banned** ve **activate** alanlarının kontrol edildiğini görüyoruz.

> Not: Bu alanların üye tablonuzda bulunması gerekiyor.


###event()

**apply** metodu içinde **conditions** metodunun sonucuna göre işlem akışlarına yön vermemize imkan sağlıyor.

```php
$glad->conditions(['banned' => 0, 'activate' => 0]);
```

Kullanıcı başarılı bir giriş yaptığında ikinci aşama hesabının durumuyla ilgili kontrol yapıyoruz.

Eğer hesabında **banned** alanı **0** ise şu event çalışacaktır:

```php
$glad->event('banned', function() {
	exit('you are banned');
});
```

Eğer hesabında **activate** alanı **0** ise şu event çalışacaktır:

```php
$glad->event('activate', function() {
	exit('you account has not activated');
});
```

###status()

Gerek üye girişi gerekse yeni bir üyelik kaydında işlemlerin sonucunu kontrol etmek için kullanabileceğimiz metodtur.

**Örnek**
```php
Glad::login(['email' => 'example@email.com', 'password' => '1234'], true);
```

Bu işlemin sonucunu kontrol etmek için:
```php
if(Glad::status() === true) {
	// do something...
}
```

**status** metodunu kullanabileceğimiz işlemler:

Name         | Description
------------ | -------
login        | Üye girişi sonrası
loginByUserId| Üye numarası ile giriş sonrası 
andLogin     | Kayıt sonrası üye giriş işleminden sonra
register     | Kayıt işlemi sonrası
change       | Kullanıcı bilgileri değiştirme işlemi sonrası

###check()

Kullanıcının sistemdeki oturumunu kontrol eder. Eğer oturumu aktif sonuç **true** döner. Aksi takdir de **false** döner.

**Örnek**

```php
if(Glad::check() === true) {
	// do something...
}
```

###guest()

Kullanıcının sistemdeki oturumunu kontrol eder. Eğer oturum aktif değilse sonuç **true** dönecektir. Aksi takdir de **false** döner.

**Örnek**
```php
if(Glad::guest() === true) {
	// do something...
}
```

###is()

Kapsayıcı bir metodtur. Check ve guest metodlarını kullanabilmektedir.

**Örnek 1**
```php
if(Glad::is('check') === true) {
	// do something...
}
```
**Örnek 2**
```php
if(Glad::is('guest') === true) {
	// do something...
}
```

###toArray()
Kullanıcının oturum verilerini **array** olarak döndürür.

Örnek:
```php
Glad::toArray();
```

###toObject()
Kullanıcının oturum verilerini **stdObject** olarak döndürür.

Örnek:
```php
Glad::toObject();
```

###toJson()
Kullanıcının oturum verilerini **JSON** olarak döndürür.

Örnek:
```php
Glad::toJson();
```

###toXml()
Kullanıcının oturum verilerini **XML** olarak döndürür.

Örnek:
```php
Glad::toXml();
```
