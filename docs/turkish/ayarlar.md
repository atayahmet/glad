Ayarlar
======

Bu bölümde database ve repository ayarları, user tablosunda bulunması gereken bazı alanların ve beni hatırla (remember) ayarlarını yapacağız.

###User tablosunda olması gereken alanlar: 

<table border="1">
<tr>
<td>Name</td>
<td>Value</td>
</tr>
<tr>
<td>identity</td>
<td>string/array</td>
</tr>
<tr>
<td>password</td>
<td>string</td>
</tr>

</table>
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