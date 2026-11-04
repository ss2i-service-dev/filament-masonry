<?php

namespace Ss2iDigital\FilamentMasonry\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use Ss2iDigital\FilamentMasonry\FilamentMasonryPlugin;

class MasonryPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('masonry')
            ->path('masonry')
            ->login()
            ->plugin(
                FilamentMasonryPlugin::make()
                    ->breakpoint('xl')
                    ->gap('2rem'),
            );
    }
}
