Hızlı Başlangıç
====

```php
use Glad\Glad;
```

**Kullanıcı girişi:**
```php
Glad::login(['email' => 'exampleuser', 'password' => '1234'], true);
```

**İşlemi doğrulama:**
```php
if(Glad::status() === true){
	// do something...
}
```
Yukarıda en basitinden bir üye giriş örneği gör
yorsunuz.

Üye girişi işlemlerini daha detaylı bir hale getirmeniz mümkün bunu detaylı kullanım kısmında bulabilirsiniz.

**Yeni Kullanıcı kayıt işlemi:**
```php
Glad::register([
	'email'	    => 'email@example.com',
	'password'  => '1234',
	'firstname' => 'Firstname',
	'lastname'  => 'Lastname'
]);
```

**İşlemi doğrulama:**

```php
if(Glad::status() === true){
	// do something...
}
```

Yukarıda kullanıcı adı olarak email tanımlandı sadece, buna email dışında kullanıcı adı ekleyerekte genişletebilirsiniz. Bunu da Ayarlar kısmında nasıl yapabileceğiniz anlatılmıştır.

**Kullanıcı bilgilerini değiştirme:**

```php
Glad::change([
	'firstname' => 'New firstname',
	'lastname'  => 'New lastname'
]);
```

Kullanıcı şifresini yine **change** methodu yardımıyla değiştirebiliriz. 
```php
Glad::change(['password'  => '123412']);
```