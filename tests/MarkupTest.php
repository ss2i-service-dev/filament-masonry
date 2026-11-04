<?php

use Livewire\Livewire;
use Ss2iDigital\FilamentMasonry\Tests\Fixtures\SectionsForm;

/*
 * Guard against Filament changing its markup: the stylesheet targets these
 * exact classes and inline style custom properties.
 */
it('renders the markup that the automatic rules target', function () {
    $html = Livewire::test(SectionsForm::class)->html();

    $grid = '//*[' . hasClass('fi-sc') . ' and ' . hasClass('fi-grid') . ' and contains(@style, "--cols-lg: repeat(2,")]';

    expect(xpath($html, $grid))->toHaveCount(1);

    $sections = $grid . '/*[' . hasClass('fi-grid-col') . ']/*[' . hasClass('fi-sc-component') . ']/*[' . hasClass('fi-sc-section') . ']';

    expect(xpath($html, $sections))->toHaveCount(3);

    // A full width child is a column with this span, which the stylesheet turns into column-span: all.
    expect(xpath($html, $grid . '/*[' . hasClass('fi-grid-col') . ' and contains(@style, "1 / -1")]'))->toHaveCount(1);
});

it('does not add any masonry class on its own', function () {
    expect(Livewire::test(SectionsForm::class)->html())->not->toContain('fi-masonry');
});
