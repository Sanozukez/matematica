<?php

namespace Lafily\BlockEditor\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;

/**
 * Bloco de Título
 * 
 * Títulos e subtítulos com níveis H2, H3, H4.
 * Suporta personalização de cor.
 */
class HeadingBlock extends AbstractBlock
{
    protected string $name = 'heading';
    protected string $label = '📌 Título';
    protected string $icon = 'heroicon-o-h1';

    public function make(): Block
    {
        return $this->createBlock()->columns(2);
    }

    protected function getSchema(): array
    {
        return [
            Select::make('level')
                ->label('Nível')
                ->options([
                    'h2' => 'H2 - Título Principal',
                    'h3' => 'H3 - Subtítulo',
                    'h4' => 'H4 - Título Pequeno',
                ])
                ->default('h2')
                ->required(),
            
            TextInput::make('content')
                ->label('Texto do Título')
                ->required()
                ->placeholder('Digite o título...')
                ->columnSpanFull(),
            
            ColorPicker::make('color')
                ->label('Cor (opcional)')
                ->nullable(),
        ];
    }
}
