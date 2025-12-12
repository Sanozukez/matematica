<?php

namespace Lafily\BlockEditor\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

/**
 * Bloco de Citação
 * 
 * Exibe citações de texto com autor e fonte opcional.
 * Ideal para destacar frases importantes ou referências.
 */
class QuoteBlock extends AbstractBlock
{
    protected string $name = 'quote';
    protected string $label = '💬 Citação';
    protected string $icon = 'heroicon-o-chat-bubble-left-right';

    public function make(): Block
    {
        return $this->createBlock()->columns(2);
    }

    protected function getSchema(): array
    {
        return [
            Textarea::make('content')
                ->label('Texto da Citação')
                ->required()
                ->rows(3)
                ->placeholder('Digite a citação...')
                ->columnSpanFull(),
            
            TextInput::make('author')
                ->label('Autor')
                ->placeholder('Nome do autor (opcional)'),
            
            TextInput::make('source')
                ->label('Fonte')
                ->placeholder('Livro, artigo, etc (opcional)'),
        ];
    }
}
