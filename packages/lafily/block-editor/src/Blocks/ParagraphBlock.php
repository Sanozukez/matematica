<?php

namespace Lafily\BlockEditor\Blocks;

use Filament\Forms\Components\Builder\Block;
use FilamentTiptapEditor\TiptapEditor;

/**
 * Bloco de Parágrafo
 * 
 * Editor de texto rico (TipTap) para parágrafos de conteúdo.
 * Suporta formatação rica, cores, alinhamento, etc.
 */
class ParagraphBlock extends AbstractBlock
{
    protected string $name = 'paragraph';
    protected string $label = '📝 Parágrafo';
    protected string $icon = 'heroicon-o-bars-3-bottom-left';

    public function make(): Block
    {
        return $this->createBlock();
    }

    protected function getSchema(): array
    {
        return [
            TiptapEditor::make('content')
                ->label('')
                ->hiddenLabel()
                ->profile('lesson')
                ->placeholder('Digite o conteúdo do parágrafo...')
                ->columnSpanFull(),
        ];
    }
}
