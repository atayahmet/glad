Glad Authentication
===================

Glad Authentication bir kimlik doğrulama kütüphanesidir. Projelerinize kolayca entegre edebilir akılda kalıcı metot isimleriyle kullanım kolaylığı sağlar.

Glad Auth sabit bir yapıdan uzak olarak tasarlandı. İstendiğinde database driver'ları istenilen database sistemlerine kolayca entegre edilebilir.

Yine aynı şekilde oturum bilgilerinin depolanmasını projenizin gereksinimlerine göre farklı repository sistemlerinde saklayabilirsiniz.

Biz varsayılan database driver'ını PDO üzerine tasarladık. Bunun yanında Laravel geliştiricilerine kolaylık olması adına Eloquent driver yaptık. 

###Kurulum

Glad Auth composer üzerinden gelmektedir. Aşağıdaki komutu terminalinizde çalıştırarak en güncel versiyona erişebilirsiniz:

```sh
composer require atayahmet/glad
```

#Paket Ayarları

Bu bölümde database ve repository ayarları, user tablosunda bulunması gereken bazı alanların ve beni hatırla (remember) ayarlarını yapacağız.

###User tablosunda olması gereken alanlar: 

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
Projeleriniz de size kolaylık sağlaması dileğiyle.
İlk olarak basitçe kayıt, kullanıcı girişi gibi metodları inceleyerek bir başlangıç yapalım.
