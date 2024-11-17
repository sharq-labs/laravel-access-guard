# Laravel Access Guard

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sharq-labs/laravel-access-guard.svg?style=flat-square)](https://packagist.org/packages/sharq-labs/laravel-access-guard)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/sharq-labs/laravel-access-guard/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/sharq-labs/laravel-access-guard/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/sharq-labs/laravel-access-guard/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/sharq-labs/laravel-access-guard/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/sharq-labs/laravel-access-guard.svg?style=flat-square)](https://packagist.org/packages/sharq-labs/laravel-access-guard)

Laravel Access Guard is a robust package to add access restrictions to specific routes in your Laravel application. It supports authentication through email and IP-based access controls, with OTP verification and browser session management.

## Features

- Access control based on email and IP.
- OTP verification for enhanced security.
- Configurable session expiration time.
- Flexible browser session tracking.
- Easy-to-use middleware for access verification.

## Installation

You can install the package via Composer:

```bash
composer require sharq-labs/laravel-access-guard
````


run the migrations:

```php
php artisan migrate
````


Publish the configuration file:
```php
php artisan vendor:publish --tag="laravel-access-guard-config"
````

## Usage
Add the VerifyAccess middleware to routes that need access restrictions:

php

```php
use Sharqlabs\LaravelAccessGuard\Http\Middleware\VerifyAccess;

Route::middleware([VerifyAccess::class])->group(function () {
    Route::get('/protected-route', [ProtectedController::class, 'index']);
});
```

## Usage in Laravel Project
After installing your package in a Laravel project, use the following commands:

Add Email:

```php
 php artisan access-guard:add-record --email="user@example.com"
```

Add IP to Whitelist:

```php
php artisan access-guard:add-record --ip="192.168.1.1" --is-whitelisted
```

Show Whitelisted IPs

```php
php artisan access-guard:show-whitelisted-ips
```
Remove Whitelisted IP
```php
php artisan access-guard:remove-whitelisted --ip="192.168.1.1"
```


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [sharq-labs](https://github.com/sharq-labs)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
