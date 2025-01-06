# A flexible and lightweight Access Control List (ACL) plugin for managing user permissions and roles in Laravel applications

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tiagolemosneitzke-filament-acl/filamentacl.svg?style=flat-square)](https://packagist.org/packages/tiagolemosneitzke-filament-acl/filamentacl)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/tiagolemosneitzke-filament-acl/filamentacl/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/tiagolemosneitzke-filament-acl/filamentacl/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/tiagolemosneitzke-filament-acl/filamentacl/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/tiagolemosneitzke-filament-acl/filamentacl/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/tiagolemosneitzke-filament-acl/filamentacl.svg?style=flat-square)](https://packagist.org/packages/tiagolemosneitzke-filament-acl/filamentacl)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require TiagoLemosNeitzke/filamentacl
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="acl"
```

This is the contents of the published config file:

```php
return [
 'permission' => [
        'prefixes' => [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'delete',
            'force_delete'
        ],
        'labels' => [
            'ver',
            'ver tudo',
            'criar',
            'atualizar',
            'restaurar',
            'apagar',
            'forçar apagar'
        ],

];
```

## Usage

```php
$filamentAcl = new TiagoLemosNeitzke/FilamentAcl\FilamentAcl();
echo $filamentAcl->echoPhrase('Hello, TiagoLemosNeitzke/FilamentAcl!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Tiago Lemos Neitzke](https://github.com/TiagoLemosNeitzke)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
