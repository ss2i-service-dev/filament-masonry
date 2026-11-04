<?php

use Ss2iDigital\FilamentMasonry\FilamentMasonryPlugin;

it('links the stylesheet and the settings in the panels that use the plugin', function () {
    $enabled = $this->get('/masonry/login')->assertOk()->getContent();

    expect($enabled)
        ->toContain('/css/ss2i-digital/filament-masonry/filament-masonry.css')
        ->toContain('<style>:root{--fi-masonry-gap:2rem;}</style>')
        ->toContain('<meta name="filament-masonry" class="fi-masonry-config fi-masonry-bp-xl fi-masonry-auto" />');
});

// One request per test: the render hooks of a panel stay registered for the rest of the process.
it('does not touch the panels that do not use the plugin', function () {
    $plain = $this->get('/plain/login')->assertOk()->getContent();

    expect($plain)
        ->not->toContain('filament-masonry')
        ->not->toContain('fi-masonry');
});

it('has sensible defaults', function () {
    $plugin = FilamentMasonryPlugin::make();

    expect($plugin->getId())->toBe('filament-masonry')
        ->and($plugin->isAuto())->toBeTrue()
        ->and($plugin->getBreakpoint())->toBe('lg')
        ->and($plugin->getGap())->toBe('1.5rem');
});

it('drops the automatic marker class when auto is disabled', function () {
    $head = FilamentMasonryPlugin::make()->auto(false)->breakpoint('md')->renderHead()->toHtml();

    expect($head)
        ->toContain('class="fi-masonry-config fi-masonry-bp-md"')
        ->not->toContain('fi-masonry-auto');
});

it('rejects an unknown breakpoint', function () {
    FilamentMasonryPlugin::make()->breakpoint('2xl');
})->throws(InvalidArgumentException::class);

it('rejects a gap that is not a CSS length', function () {
    FilamentMasonryPlugin::make()->gap('1rem;} body{display:none');
})->throws(InvalidArgumentException::class);
