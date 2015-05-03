Metotlar
=======

###login

Kayıtlı kullanıcıların bizde muhafaza edilen üyelik bilgileriyle karşılaştırma yapar işlem başarılıysa sistemde kullanıcı oturumunu başlatır.

İki para metre almaktadır. Bunlar;

Name     | Type | Description
-------- | ------|------------
identity  | array | Kullanıcının giriş bilgilerini içerir. Dizi olarak gönderilmelidir.
remember | bool  | Beni Hatırla özelliğinin çalışması. Varsayılan parametre **false**


return  | Description
--------|
instance| Glad class'sının örneği dönmektedir.

**Örnek**

```php
Glad::login(['username' => 'exampleuser', 'password' => '1234'], true);
```

Yukarıdaki işlemin sonucunu kontrol etmek için;

```php
if(Glad::status() === true) {
	// do something...
}
```

> Not: Giriş esnasında belirttiğiniz kullanıcı adı alanı yada email alanının setup metodunda **fields** alanına tanımladığınız alanlarla örtüştüğünden emin olunuz. Bunların en az bir tanesini eğer isteseniz tamamını giriş koşulu olarak kullanabilirsiniz.

###loginByUserId

Metodun isminden de anlaşılacağı gibi kullanıcıyı üye numarası (ID) ile giriş yapmasına imkan sağlar. Bazı durumlar da kullanıcıyı üyelik bilgilerini girmesini istemeden sistemde oturum açmasını isteyebiliriz.

Parametreler:

Name     | Type | Description
-------- | ------|------------
User id  | mixed | Üyenin kullanıcılar tablosunda ki numarası (User Id)

return  | Description
--------|
instance| Glad class'sının örneği dönmektedir.

**Örnek**
```php
Glad::loginByUserId(1);
```

ya da;

```php
Glad::loginByUserId('507f191e810c19729de860ea');
```

Bu işlemin sonucunu şu şekilde kontrol edebiliriz:
```php
if(Glad::status() === true) {
	// do something...
}
```

ya da;
```php
if(Glad::loginByUserId(1)->status() === true) {
	// do something...
}
```

###andLogin

Normal de sisteme yeni bir üye eklendiğin de (bunu register metodu ile yaptığınızı varsayıyoruz) kullanıcı sadece kayıt edilir. Sistem de oturumda açılmasını istersek **andLogin** metodunu kullanmanız gerekiyor.

return  | Description
--------|
instance| Glad class'sının örneği dönmektedir.

**Kullanım özeti:**
```php
Glad::register([])->andLogin();
```
ya da;
```php
Glad::register([]);

Glad::andLogin();
```

> Not: **andLogin** metodu her hangi bir parametre almamaktadır.


**Örnek**
```php
Glad::register([
	'email'	    => 'email@example.com',
	'password'  => '1234',
	'firstname' => 'Firstname',
	'lastname'  => 'Lastname'
])->andLogin();
```

İşlem sonucunu kontrol etmek için:
```php
if(Glad::status() === true) {
	// do something...
}
```

###logout

Bir kullanıcının sistemdeki oturumunu tamamen kapatır. Bununla birlikte kullanıcı Beni Hatır'la özelliğini kullanıyorsa hemen sonlandırılır. Üyenin tekrar girişi yapması istenir.

return  | Description
--------|
bool    | true/false

**Örnek**
```php
Glad::logout();
```

###register

Kullanıcı kayıt işlemini gerçekleştirir.


**Parametreler**

Name         | Type | Description
------------ | ------|------------
credentials  | array | Kayıt için gerekli bilgiler

return  | Description
--------|
instance| Glad class'sının örneği dönmektedir.

**Örnek:**
```php
Glad::register([
	'email'	    => 'email@example.com',
	'password'  => '1234',
	'firstname' => 'Firstname',
	'lastname'  => 'Lastname'
]);
```

İşlem sonucunu kontrol etmek için:
```php
if(Glad::status() === true) {
	// do something...
}
```

###change
Sistemdeki kullanıcıların bilgileri buna şifreleride dahil değiştirmek istediğimizde **change** metodu burada bize bunu sağlamaktadır.

return  | Description
--------|
instance| Glad class'sının örneği dönmektedir

**Parametreler**

Name         | Type | Description
------------ | ------|------------
credentials  | array | Yeni kullanıcı bilgileri

```php
Glad::change([
	'email'	    => 'email@example.com',
	'password'  => '1234',
	'firstname' => 'Firstname',
	'lastname'  => 'Lastname'
]);
```

İşlem sonucunu kontrol etmek için:
```php
if(Glad::status() === true) {
	// do something...
}
```

> Not: Yeni bilgilerin daha önce kayıt işleminde alanları belirtilmiş olması gerekiyor.

###apply

Üyelik sistemlerinde detaylı çapraz kontroller olmazsa olmazlardan diyebiliriz. Glad auth burada sizlere apply metoduyla detaylı kontroller ve bu kontoller sonrası davranışları yönetmenizi sağlıyor. 

**apply** metodu parametre olarak **Closure** methodu almaktadır:

Name         | Type   | Description
------------ | -------|------------
Closure      | object | Kontrolleri içerir

return  | Description
--------|
instance| Glad class'sının örneği dönmektedir

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

**conditions**

Üyelik sistemlerin de kullanıcıların durumlarıyla ilgili bir takım sınıflandırmalar yapılmaktadır. Mesela üyeliğini henüz aktifleştirmemiş olan kullanıcılar için burada bir filtre uygulayabilirsiniz. 

```php
$glad->conditions(['banned' => 0, 'activate' => 1]);
```
Örnekte banned ve activate alanlarının kontrol edildiğini görüyoruz.

> Not: Bu alanların üye tablonuzda bulunması gerekiyor.

**event**

Üye girişi esnasında bir takım sorgulamalarımızın sonucunda davranışları yönetmek için **event** metodu burada bize yardımcı oluyor. 

```php
$glad->event('banned', function() {
	exit('you are banned');
});

$glad->event('activate', function() {
	exit('your account has activated');
});
```
Yukarıdaki örnekte **conditions** metodu ile **banned** ve **activate** alanlarına birer şart ekledik. 

Bu şartların her hangi biri eşleştiğinde **event** metodu işlemlerimize yön vermemizi sağlıyor. Biz şimdilik sadece **exit** ile bir mesaj çıktısı aldık.

###event