# Laravel-sdk


## 📝 Introduction

An API wrapper for nstack.io API

## 📦 Installation

To install this package you will need:

* PHP 7.1+

Run 

`composer require nstack/laravel-sdk`

or setup in composer.json

`nstack/laravel-sdk: 1.0.x`


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

or via globa func

```php
nstack()->getContinentsClient()->index()
```

All PHP functionality can be found

[Link](https://github.com/nstack-io/php-sdk)



## 🏆 Credits

This package is developed and maintained by the PHP team at [Nodes](http://nodesagency.com)

## 📄 License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
