<?php

beforeEach(function () {
    $this->css = preg_replace('/\s+/', '', file_get_contents(__DIR__ . '/../resources/dist/filament-masonry.css'));
});

it('ships a precompiled stylesheet', function () {
    expect($this->css)->not->toBeEmpty()->and($this->css)->not->toContain('@apply');
});

it('targets the markup of Filament grids and sections', function (string $selector) {
    expect($this->css)->toContain($selector);
})->with([
    'explicit wrapper' => '.fi-masonry>.fi-sc.fi-grid[style*="--cols-lg:repeat(2,"]',
    'three columns' => '--cols-lg:repeat(3,"]',
    'md breakpoint' => '--cols-md:repeat(2,"]',
    'xl breakpoint' => '--cols-xl:repeat(3,"]',
    'sections only' => '.fi-grid-col:not(.fi-hidden)>.fi-sc-component>.fi-sc-section',
    'exclusion' => ':not(.fi-masonry-disabled,.no-masonry)',
    'automatic scope' => ':root:has(>head>.fi-masonry-config.fi-masonry-auto.fi-masonry-bp-lg)',
    'full width children' => 'column-span:all',
    'no break in a section' => 'break-inside:avoid',
]);

it('uses the gap custom property and the lg media query', function () {
    expect($this->css)
        ->toContain('var(--fi-masonry-gap,1.5rem)')
        ->toContain('@media(min-width:1024px)')
        ->toContain('@media(min-width:768px)')
        ->toContain('@media(min-width:1280px)');
});

it('upgrades to native masonry where it exists', function () {
    expect($this->css)
        ->toContain('@supports(display:grid-lanes)')
        ->toContain('display:grid-lanes;gap:var(--fi-masonry-gap')
        ->toContain('@supportsnot(display:grid-lanes)');
});

it('has balanced braces, so that no rule swallows the following ones', function () {
    expect(substr_count($this->css, '{'))->toBe(substr_count($this->css, '}'));
});
