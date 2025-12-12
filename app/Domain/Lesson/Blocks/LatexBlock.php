<?php

namespace App\Domain\Lesson\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

/**
 * Bloco de Fórmula LaTeX
 * 
 * Renderização de fórmulas matemáticas usando sintaxe LaTeX.
 * Suporta modo inline e display (centralizado).
 */
class LatexBlock extends AbstractBlock
{
    protected string $name = 'latex';
    protected string $label = '∑ Fórmula (LaTeX)';
    protected string $icon = 'heroicon-o-variable';

    public function make(): Block
    {
        return $this->createBlock();
    }

    protected function getSchema(): array
    {
        return [
            Textarea::make('content')
                ->label('Código LaTeX')
                ->required()
                ->rows(4)
                ->placeholder('Ex: \\frac{a}{b} ou \\sum_{i=1}^{n} x_i')
                ->helperText('Use sintaxe LaTeX. Exemplos: \\frac{a}{b}, \\sqrt{x}, x^{2}, \\int_{a}^{b}')
                ->columnSpanFull(),
            
            Toggle::make('display_mode')
                ->label('Exibir Centralizado (Display Mode)')
                ->helperText('Desativado: inline (dentro do texto). Ativado: bloco centralizado')
                ->default(true),
            
            TextInput::make('caption')
                ->label('Legenda')
                ->placeholder('Ex: Teorema de Pitágoras')
                ->columnSpanFull(),
        ];
    }
}
