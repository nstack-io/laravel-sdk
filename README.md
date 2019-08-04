# php-sdk


## 📝 Introduction

An API wrapper for nstack.io API

## 📦 Installation

To install this package you will need:

* PHP 7.1+

Run 

`composer require nstack/laravel-sdk`

or setup in composer.json

`nstack/laravel-sdk: 1.0.x`

## ⚙ Usage

Setup in config/app.php

```php

'providers' => 
[
    ....
    NStack\ServiceProvider::class
]

'aliases' => 
[
    ....
    NStack\Facade::class
]

```

## 🏆 Credits

This package is developed and maintained by the PHP team at [Nodes](http://nodesagency.com)

## 📄 License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
