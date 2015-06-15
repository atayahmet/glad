Kurulum
===

Glad Auth composer üzerinden gelmektedir. Aşağıdaki komutu terminalinizde çalıştırarak en güncel versiyona erişebilirsiniz:

Aşağıdaki bloğu **composer.json** dosyasında **require** alanına ekleyin:
```php
"atayahmet/glad": "1.0.*@dev"
```

Sonra şu komutu çalıştırın:
```php
$ composer update
```

Projenize yüklemek için:
```sh
use Glad\Glad;
```