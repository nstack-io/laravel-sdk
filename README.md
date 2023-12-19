# Laravel-sdk

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nstack-io/laravel-sdk/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nstack-io/laravel-sdk/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/nstack-io/laravel-sdk/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/nstack-io/laravel-sdk/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/nstack-io/laravel-sdk/badges/build.png?b=master)](https://scrutinizer-ci.com/g/nstack-io/laravel-sdk/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/nstack-io/laravel-sdk/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

## 📝 Introduction

An API wrapper for nstack.io API

## 📦 Installation

To install this package you will need:

* PHP 7.1+

Run

`composer require nstack/laravel-sdk`

or setup in composer.json

`nstack/laravel-sdk: 1.0.x`

In `config/app.php` (Laravel) or `bootstrap/app.php` (Lumen) you should replace Laravel's translation service provider

```php
Illuminate\Translation\TranslationServiceProvider::class,
```

by the one included in this package:

```php
NStack\ServiceProvider::class
```

Setup in config/app.php

```php

'providers' =>
[
    ....
    // Illuminate\Translation\TranslationServiceProvider::class
    NStack\ServiceProvider::class
]

'aliases' =>
[
    ....
    'NStack'       => NStack\Facade::class,
]

```

Copy config over from vendor/nstack/config/nstack.php to project/config/nstack.php

```
php artisan vendor:publish --provider="NStack\ServiceProvider"

```

## ⚙ Usage

You can now call via facade, eg:

````php
\NStack::getContinentsClient()->index()
````

or via global function

```php
nstack()->getContinentsClient()->index()
```


or via integration with `trans()` [helper](https://laravel.com/docs/5.8/helpers#method-trans)

```php
echo trans('messages.welcome');
```


All the basic fuctionality can be found in the php-sdk

## Features

    [x] Register php-sdk in laravel provider, with Facade and global func
    [x] Translate provider for localization using laravels global func: trans(key)    

[Link here](https://github.com/nstack-io/php-sdk)

All PHP functionality can be found

[Link](https://github.com/nstack-io/php-sdk)

## 🏆 Credits

This package is developed and maintained by the PHP team at [Monstarlab](http://monstar-lab.com)

## 📄 License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
