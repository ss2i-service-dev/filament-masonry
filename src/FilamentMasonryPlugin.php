<?php

namespace Ss2iDigital\FilamentMasonry;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Facades\FilamentAsset;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
use InvalidArgumentException;

class FilamentMasonryPlugin implements Plugin
{
    public const BREAKPOINTS = ['md', 'lg', 'xl'];

    protected bool $isAuto = true;

    protected string $breakpoint = 'lg';

    protected string $gap = '1.5rem';

    public function getId(): string
    {
        return 'filament-masonry';
    }

    public function register(Panel $panel): void
    {
        // The stylesheet is registered as "loaded on request" by the service
        // provider, so only the panels that use this plugin link it.
        $panel->renderHook(PanelsRenderHook::HEAD_END, fn (): HtmlString => $this->renderHead());
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

    /**
     * Stack the sections of every 2 or 3 column grid made only of sections,
     * without declaring anything in the schema.
     */
    public function auto(bool $condition = true): static
    {
        $this->isAuto = $condition;

        return $this;
    }

    /**
     * The Filament breakpoint at which grids declare their columns (md, lg or xl).
     */
    public function breakpoint(string $breakpoint): static
    {
        if (! in_array($breakpoint, self::BREAKPOINTS, true)) {
            throw new InvalidArgumentException('The masonry breakpoint must be one of: ' . implode(', ', self::BREAKPOINTS) . '.');
        }

        $this->breakpoint = $breakpoint;

        return $this;
    }

    /**
     * Any CSS length, for example `1rem`, `24px` or `calc(1rem + 2px)`.
     */
    public function gap(string $gap): static
    {
        if (! preg_match('/^[a-z0-9.%\s()+*\/-]+$/i', $gap)) {
            throw new InvalidArgumentException('The masonry gap must be a CSS length.');
        }

        $this->gap = $gap;

        return $this;
    }

    public function isAuto(): bool
    {
        return $this->isAuto;
    }

    public function getBreakpoint(): string
    {
        return $this->breakpoint;
    }

    public function getGap(): string
    {
        return $this->gap;
    }

    /**
     * The stylesheet, the settings as CSS variables, and a marker element
     * that the stylesheet uses to scope its automatic rules (`:has()`).
     */
    public function renderHead(): HtmlString
    {
        $href = FilamentAsset::getStyleHref(
            FilamentMasonryServiceProvider::$assetId,
            FilamentMasonryServiceProvider::$assetPackage,
        );

        $classes = 'fi-masonry-config fi-masonry-bp-' . $this->breakpoint . ($this->isAuto ? ' fi-masonry-auto' : '');

        return new HtmlString(
            '<link rel="stylesheet" href="' . e($href) . '" data-navigate-track />' .
            '<style>:root{--fi-masonry-gap:' . e($this->gap) . ';}</style>' .
            '<meta name="filament-masonry" class="' . $classes . '" />'
        );
    }
}
