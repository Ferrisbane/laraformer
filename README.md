# Laraformer
A Laravel package that makes 'terraforming' forms easy.

- [Installation](#installation)

## Installation
This package requires PHP 5.4+.

To install through composer you can either use `composer require Ferrisbane/laraformer` or include the package in your `composer.json`.

```php
"Ferrisbane/laraformer": "0.1.*"
```

Then run either `composer install` or `composer update` to download the package.

To use the package with Laravel 5 add the laraformer service provider to the list of service providers in `config/app.php`.

```php
'providers' => [
  'Ferrisbane\Laraformer\Laravel5ServiceProvider'
];
```

Then add the `Laraformer` facade to the aliases array.

```php
'aliases' => [
  'Laraformer' => 'Ferrisbane\Laraformer\Facades\Laraformer',
];
```

Then use `php artisan vendor:publish` to publish the config and database migrations. 

The package comes with database migrations. To run the migrations use `php artisan migrate` to set up the laraformer database tables.

To use the package you can either use the `Laraformer` facade or if you prefer using dependency injection laraformer is bound to the IOC container by its interface.