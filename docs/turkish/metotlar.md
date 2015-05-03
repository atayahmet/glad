Metotlar
=======

###login

Kayıtlı kullanıcıların bizde muhafaza edilen üyelik bilgileriyle karşılaştırma yapar işlem başarılıysa sistemde kullanıcı oturumunu başlatır.

İki para metre almaktadır. Bunlar;

Name     | Value | Description
-------- | ------|------------
identity  | array | Kullanıcının giriş bilgilerini içerir. Dizi olarak gönderilmelidir.
remember | bool  | Beni Hatırla özelliğinin çalışması. Varsayılan parametre **false**


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

Name     | Value | Description
-------- | ------|------------
User id  | mixed | Üyenin kullanıcılar tablosunda ki numarası (User Id)

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
if(Glad::status === true) {
	// do something...
}
```

ya da;
```php
if(Glad::loginByUserId(1)->status() === true) {
	// do something...
}
```