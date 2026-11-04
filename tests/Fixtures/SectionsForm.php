<?php

namespace Ss2iDigital\FilamentMasonry\Tests\Fixtures;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Ss2iDigital\FilamentMasonry\Components\Masonry;

class SectionsForm extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public string $variant = 'plain';

    public function mount(string $variant = 'plain'): void
    {
        $this->variant = $variant;
    }

    /**
     * @return array<int, Section>
     */
    protected function sections(): array
    {
        return [
            Section::make('Identity')->schema([TextInput::make('name'), TextInput::make('email')]),
            Section::make('Address')->schema([TextInput::make('street')]),
            Section::make('Notes')->schema([TextInput::make('notes')])->columnSpanFull(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        $grid = match ($this->variant) {
            'component' => Masonry::make($this->sections())->columns(3),
            'forced' => Grid::make(2)->masonry()->schema($this->sections()),
            'excluded' => Grid::make(2)->masonry(false)->schema($this->sections()),
            default => Grid::make(2)->schema($this->sections()),
        };

        return $schema
            ->components([$grid])
            ->statePath('data');
    }

    public function render(): View
    {
        return view('masonry-test::sections-form');
    }
}
