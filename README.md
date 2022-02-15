# 🍂 LiveIntent PHP code style rules

[![Latest Version on Packagist](https://img.shields.io/packagist/v/liveintent/php-cs-fixer.svg?style=flat-square)](https://packagist.org/packages/liveintent/php-cs-fixer)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/liveintent/php-cs-fixer/run-tests?label=tests)](https://github.com/liveintent/php-cs-fixer/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/liveintent/php-cs-fixer/run-lint?label=code%20style)](https://github.com/liveintent/php-cs-fixer/actions?query=workflow%3Arun-lint+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/liveintent/php-cs-fixer.svg?style=flat-square)](https://packagist.org/packages/liveintent/php-cs-fixer)

This package contains a code style linter for LiveIntent PHP projects. Under the hood it simply wraps [php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer), configures it with our agreed rule set, and adds some of our own custom rules.

## Installation

You can install the package via composer:

```bash
composer require liveintent/php-cs-fixer
```

## Usage

Create a `.php-cs-fixer.dist.php` file at the root of your project with the following contents:

```php
<?php

$finder = Symfony\Component\Finder\Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new LiveIntent\PhpCsFixer\Config())->setFinder($finder);
```

Adjust the folders to analyze as needed.

## Development

Clone this repository and install dependencies via:
```sh
composer install
```

## Testing

You can run the tests via:

```sh
composer test
```

## Linting

The installed linter will auto-format your code.

You can run it via:

```sh
composer lint
```
