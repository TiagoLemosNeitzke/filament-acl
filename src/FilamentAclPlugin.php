<?php

namespace TiagoLemosNeitzke\FilamentAcl;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentAclPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-acl';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            Resources\RoleResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
