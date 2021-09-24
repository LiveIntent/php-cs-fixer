# :package_description

[![Latest Version on Packagist](https://img.shields.io/packagist/v/:vendor_slug/:package_slug.svg?style=flat-square)](https://packagist.org/packages/:vendor_slug/:package_slug)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/:vendor_slug/:package_slug/run-tests?label=tests)](https://github.com/:vendor_slug/:package_slug/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/:vendor_slug/:package_slug/run-lint?label=code%20style)](https://github.com/:vendor_slug/:package_slug/actions?query=workflow%3Arun-lint+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/:vendor_slug/:package_slug.svg?style=flat-square)](https://packagist.org/packages/:vendor_slug/:package_slug)

This is where your description should go.

## Installation

You can install the package via composer:

```bash
composer require :vendor_slug/:package_slug
```

## Usage

```php
$package = new VendorName\Skeleton();
echo $package->echoPhrase('Hello, VendorName!');
```

## Development

Clone this repository and install dependencies via:
```sh
composer install
```

When developing a laravel package, it's often useful to be able to develop your package alongside a laravel app without.

With composer you can symlink the package you are developing into the dependencies of your laravel package by updating your app's `composer.json` file.

```json
{
  "repositories": [
    {
        "type": "path",
        "url": "../../packages/my-package"
    }
  ],
  "require": {
    "my/package": "*"
  }
}
```

## Testing

You can run the tests via:

```sh
composer test

# or directly via
vendor/bin/phpunit
```

Additionally, you may run the tests in 'watch' mode via:

```sh
composer test-watch
```

## Linting

The installed linter will auto-format your code to comply with our agreed [php coding standard](https://github.com/LiveIntent/php-cs-rules/blob/master/rules.php).

You can run it via:

```sh
composer lint
```
