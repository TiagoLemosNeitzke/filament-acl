# Filament ACL - Access Control List for Laravel

A flexible and lightweight Access Control List (ACL) plugin for managing user permissions and roles in Laravel applications using Filament.

## Attention
This package doesn't work with Tenancy.

---

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tiagolemosneitzke/filamentacl.svg?style=flat-square)](https://packagist.org/packages/tiagolemosneitzke/filamentacl)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/TiagoLemosNeitzke/filamentacl/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/TiagoLemosNeitzke/filamentacl/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/TiagoLemosNeitzke/filamentacl/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/TiagoLemosNeitzke/filamentacl/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/TiagoLemosNeitzke/filamentacl.svg?style=flat-square)](https://packagist.org/packages/tiagolemosneitzke/filamentacl)

## Installation

You can install the package via composer:

```bash
composer require tiagolemosneitzke/filamentacl -W
```

This will install the Filament ACL package along with its dependencies, including `spatie/laravel-permission`.

After the installation, publish the migration and configuration files:

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

Run the migrations to create the necessary tables:

```bash
php artisan migrate
```

Add the `HasRoles` trait to your User model:

```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
}
```

Register the plugin in your Panel provider:

```php
use TiagoLemosNeitzke\FilamentAcl\FilamentAclPlugin;
 
public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
             FilamentAclPlugin::make()
        ]);
}
```

You can then install the Filament ACL package with the following command:

```bash
php artisan filament-acl:install
```

This will publish the config file.

Alternatively, you can publish only the config file with:

```bash
php artisan vendor:publish --tag="filament-acl-config"
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
        ]
    ],

    'roles_prefixes' => [
        'admin',
    ]
];
```
Also, you can publish manually all the files with:

```bash
php artisan vendor:publish --provider="TiagoLemosNeitzke\FilamentAcl\FilamentAclServiceProvider"
```
Or you can publish the necessary files one by one
```bash
php artisan vendor:publish --tag=filament-acl-config
php artisan vendor:publish --tag=filament-acl-stubs
```

You don't need to publish all the files for the package work, but you need to publish the settings file and configuring correctly the Laravel Permissions Package.

In your `UserResource` file, you should add the below code to edit and view the permission of the user:

```php
use TiagoLemosNeitzke\FilamentAcl\Models\Role;

public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('roles')
                    ->label('Role')
                    ->relationship('roles', 'name')
                    ->options(Role::all()->pluck('name', 'id'))
                    ->multiple()
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Is Admin?')
                    ->badge(),
             ]);
    }
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
