<?php

use Filament\Schemas\Components\Grid;
use Livewire\Livewire;
use Ss2iDigital\FilamentMasonry\Components\Masonry;
use Ss2iDigital\FilamentMasonry\Tests\Fixtures\SectionsForm;

it('marks the wrapper of the Masonry component', function () {
    $html = Livewire::test(SectionsForm::class, ['variant' => 'component'])->html();

    $wrapper = '//*[' . hasClass('fi-masonry') . ']';

    expect(xpath($html, $wrapper))->toHaveCount(1)
        ->and(xpath($html, $wrapper . '/*[' . hasClass('fi-sc') . ' and ' . hasClass('fi-grid') . ' and contains(@style, "--cols-lg: repeat(3,")]'))
        ->toHaveCount(1)
        ->and(xpath($html, $wrapper . '//*[' . hasClass('fi-sc-section') . ']'))
        ->toHaveCount(3);
});

it('defaults the Masonry component to two columns that span the whole row', function () {
    $grid = Masonry::make([]);

    expect($grid)->toBeInstanceOf(Grid::class)
        ->and($grid->getExtraAttributes()['class'])->toBe('fi-masonry')
        ->and($grid->getColumns())->toBe(['lg' => 2]);
});

it('forces masonry with the macro', function () {
    $html = Livewire::test(SectionsForm::class, ['variant' => 'forced'])->html();

    expect(xpath($html, '//*[' . hasClass('fi-masonry') . ']'))->toHaveCount(1)
        ->and(xpath($html, '//*[' . hasClass('fi-masonry-disabled') . ']'))->toHaveCount(0);
});

it('excludes a grid with masonry(false)', function () {
    $html = Livewire::test(SectionsForm::class, ['variant' => 'excluded'])->html();

    expect(xpath($html, '//*[' . hasClass('fi-masonry-disabled') . ']'))->toHaveCount(1)
        ->and(xpath($html, '//*[' . hasClass('fi-masonry') . ']'))->toHaveCount(0);
});

it('keeps the classes already set on the grid', function () {
    $grid = Grid::make(2)->extraAttributes(['class' => 'my-grid'])->masonry();

    expect($grid->getExtraAttributes()['class'])->toContain('my-grid')->toContain('fi-masonry');
});
