<?php

namespace TiagoLemosNeitzke\FilamentAcl;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use TiagoLemosNeitzke\FilamentAcl\Commands\FilamentAclCommand;
use TiagoLemosNeitzke\FilamentAcl\Testing\TestsFilamentAcl;

class FilamentAclServiceProvider extends PackageServiceProvider
{
    public static string $name = 'FilamentAcl';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasConfigFile(['acl'])
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('TiagoLemosNeitzke/filament-acl');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }
    }

    public function packageRegistered(): void {
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
                ], 'filamentacl-stubs');
            }
        }
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
