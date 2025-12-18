<?php

namespace Ymkn\BlockEditor\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;

/**
 * Bloco de Tabela
 * 
 * Criação de tabelas com cabeçalhos e linhas configuráveis.
 * Ideal para comparações e dados tabulares.
 */
class TableBlock extends AbstractBlock
{
    protected string $name = 'table';
    protected string $label = '📊 Tabela';
    protected string $icon = 'heroicon-o-table-cells';

    public function make(): Block
    {
        return $this->createBlock();
    }

    protected function getSchema(): array
    {
        return [
            TextInput::make('caption')
                ->label('Título da Tabela')
                ->placeholder('Ex: Comparação de Valores')
                ->columnSpanFull(),
            
            Repeater::make('headers')
                ->label('Cabeçalhos (Colunas)')
                ->schema([
                    TextInput::make('text')
                        ->label('Nome da Coluna')
                        ->required(),
                ])
                ->minItems(2)
                ->defaultItems(3)
                ->addActionLabel('Adicionar Coluna')
                ->columnSpanFull()
                ->grid(3),
            
            Repeater::make('rows')
                ->label('Linhas')
                ->schema([
                    Repeater::make('cells')
                        ->label('Células')
                        ->schema([
                            TextInput::make('value')
                                ->label('Valor'),
                        ])
                        ->grid(3)
                        ->columnSpanFull(),
                ])
                ->minItems(2)
                ->defaultItems(3)
                ->addActionLabel('Adicionar Linha')
                ->collapsible()
                ->columnSpanFull(),
        ];
    }
}

