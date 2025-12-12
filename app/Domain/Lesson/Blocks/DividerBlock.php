<?php

namespace App\Domain\Lesson\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;

/**
 * Bloco de Divisor
 * 
 * Linhas separadoras ou espaços em branco para organizar o conteúdo.
 * Diferentes estilos visuais disponíveis.
 */
class DividerBlock extends AbstractBlock
{
    protected string $name = 'divider';
    protected string $label = '━ Divisor';
    protected string $icon = 'heroicon-o-minus';

    public function make(): Block
    {
        return $this->createBlock();
    }

    protected function getSchema(): array
    {
        return [
            Select::make('style')
                ->label('Estilo')
                ->options([
                    'solid' => '━━━ Linha Sólida',
                    'dashed' => '╌╌╌ Linha Tracejada',
                    'dotted' => '┈┈┈ Linha Pontilhada',
                    'thick' => '═══ Linha Grossa',
                    'space' => '      Espaço em Branco',
                ])
                ->default('solid'),
        ];
    }
}
