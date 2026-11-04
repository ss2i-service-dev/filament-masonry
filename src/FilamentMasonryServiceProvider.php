<?php

namespace Ss2iDigital\FilamentMasonry;

use Filament\Schemas\Components\Grid;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentMasonryServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-masonry';

    public static string $assetId = 'filament-masonry';

    public static string $assetPackage = 'ss2i-digital/filament-masonry';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name);
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            // Not rendered by @filamentStyles: the plugin links it in the panels that enable it.
            Css::make(static::$assetId, __DIR__ . '/../resources/dist/filament-masonry.css')->loadedOnRequest(),
        ], static::$assetPackage);

        Grid::macro(
            'masonry',
            // Macros are bound to the grid they are called on.
            // @phpstan-ignore method.notFound
            fn (bool $condition = true): Grid => $this->extraAttributes([
                'class' => $condition ? 'fi-masonry' : 'fi-masonry-disabled',
            ], merge: true)
        );
    }
}
