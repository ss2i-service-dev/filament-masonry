<?php

declare(strict_types=1);

namespace Ss2iDigital\FilamentMasonry\Components;

use Filament\Schemas\Components\Grid;

/**
 * Sections laid out as masonry: each one is placed under the previous one of
 * its column instead of waiting for the end of the row. Works in forms and
 * infolists; use `->columns(3)` (or `->columns(['default' => 1, 'md' => 2])`)
 * to change the number of columns.
 */
class Masonry
{
    /**
     * @param  array<mixed>  $components
     */
    public static function make(array $components = []): Grid
    {
        return Grid::make(2)
            ->extraAttributes(['class' => 'fi-masonry'], merge: true)
            ->columnSpanFull()
            ->schema($components);
    }
}
