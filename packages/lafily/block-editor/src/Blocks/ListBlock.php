<?php

namespace Lafily\BlockEditor\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;

/**
 * Bloco de Lista
 * 
 * Criação de listas com diferentes estilos:
 * - Marcadores (bullets)
 * - Numerada
 * - Checklist
 */
class ListBlock extends AbstractBlock
{
    protected string $name = 'list';
    protected string $label = '📋 Lista';
    protected string $icon = 'heroicon-o-list-bullet';

    public function make(): Block
    {
        return $this->createBlock();
    }

    protected function getSchema(): array
    {
        return [
            Select::make('style')
                ->label('Tipo de Lista')
                ->options([
                    'bullet' => '• Marcadores',
                    'numbered' => '1. Numerada',
                    'checklist' => '☑ Checklist',
                ])
                ->default('bullet')
                ->required(),
            
            Repeater::make('items')
                ->label('Itens')
                ->schema([
                    RichEditor::make('content')
                        ->label('')
                        ->required()
                        ->toolbarButtons(['bold', 'italic', 'link'])
                        ->placeholder('Digite o item da lista...'),
                ])
                ->minItems(1)
                ->defaultItems(2)
                ->addActionLabel('Adicionar Item')
                ->collapsible()
                ->columnSpanFull(),
        ];
    }
}
