<?php

namespace TiagoLemosNeitzke\FilamentAcl;

use Illuminate\Filesystem\Filesystem;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use TiagoLemosNeitzke\FilamentAcl\Commands\FilamentAclCommand;

class FilamentAclServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-acl';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasConfigFile(['acl'])
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('TiagoLemosNeitzke/filament-acl');
            });
    }

    public function packageRegistered(): void
    {
        parent::packageRegistered();

        $this->app->scoped('filament-acl', function (): FilamentAcl {
            return new FilamentAcl;
        });
    }

    public function packageBooted(): void
    {
        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filamentAcl/{$file->getFilename()}"),
                ], 'filament-acl-stubs');
            }
        }

        // handle config
        $this->publishes([
            __DIR__ . '/../config/acl.php' => config_path('acl.php'),
        ], 'filament-acl-config');
    }

    protected function getAssetPackageName(): ?string
    {
        return 'TiagoLemosNeitzke/FilamentAcl';
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentAclCommand::class,
        ];
    }
}
