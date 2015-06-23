Glad Authentication
===================

[![Documentation Status](https://readthedocs.org/projects/glad/badge/?version=latest)](https://readthedocs.org/projects/glad/?badge=latest) [![Build Status](https://scrutinizer-ci.com/g/atayahmet/glad/badges/build.png?b=master)](https://scrutinizer-ci.com/g/atayahmet/glad/build-status/master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/atayahmet/glad/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/atayahmet/glad/?branch=master)  [![Total Downloads](https://img.shields.io/packagist/dt/atayahmet/glad.svg?style=flat-square)](https://packagist.org/packages/atayahmet/glad) [![License](https://img.shields.io/packagist/l/atayahmet/glad.svg?style=flat-square)](https://packagist.org/packages/atayahmet/glad) [![Code Climate](https://codeclimate.com/github/atayahmet/glad/badges/gpa.svg)](https://codeclimate.com/github/atayahmet/glad)

It is an authentication package for your project you can quickly and simply integrated. Whether composer supported the use of any framework or use native PHP projects, establishing the Composer.

Below you will find links on how to use a few framework.

# Features
- Simple and permanent method names in mind.
- It can be used quickly with a few small configuration.
- Customizable usage.
- The flexible structure can create their own provider you can encode several classes again on this occasion.
- Cache flexibility in the structure and to provide a quick start with the hosting service in the current cache.
- All classes with attentive and intelligent testing scenarios to be written.


# Install

Glad Auth package you need to add to your project with the composer. If you have not used before, composer or composer is not installed, you can start from the following link: [![Composer]()](http://getcomposer.org/)

add to section require in composer.json:
```php
"atayahmet/glad" : "1.0.*@dev"
```

after run the:
```php
$ composer update
```

**login:**
```php
use Glad\Glad;

Glad::login(['email' => 'ali.yildiz@example.com', 'password' => '1234123']);

if(Glad::status() === true) {
	
	// do something...

}
```

**register**

```php
use Glad\Glad;

Glad::register([
	'firstname' => 'Can',
	'lastname'	=> 'ÖZTÜRK',
	'email'		=> 'can.ozturk@example.com',
	'password'	=> '123412'
]);

if(Glad::status() === true) {
	
	// do something...

}

```

#Documentation
Documentation is written in readthedocs.org.

[- Click for Documentation](http://glad.readthedocs.org/en/master/)

#Use in Frameworks (Demos)

In terms of the integration it is made by way of example below with a few framework.

We hope that is helpful.

[- Laravel 5.1](https://github.com/atayahmet/Glad-Demos/tree/master/Laravel5.1)

[- CodeIgniter 3](https://github.com/atayahmet/Glad-Demos/tree/master/CodeIgniter3)

[- FuelPHP 1.7](https://github.com/atayahmet/Glad-Demos/tree/master/FuelPHP1.7)

Gitter:

[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/atayahmet/glad?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=body_badge)