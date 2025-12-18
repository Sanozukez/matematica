<?php

namespace Ymkn\BlockEditor\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

/**
 * Bloco de Alerta/Aviso
 * 
 * Destaca informações importantes com diferentes níveis:
 * - Info (azul)
 * - Sucesso (verde)
 * - Aviso (amarelo)
 * - Perigo (vermelho)
 */
class AlertBlock extends AbstractBlock
{
    protected string $name = 'alert';
    protected string $label = '⚠️ Alerta/Aviso';
    protected string $icon = 'heroicon-o-exclamation-triangle';

    public function make(): Block
    {
        return $this->createBlock();
    }

    protected function getSchema(): array
    {
        return [
            Select::make('type')
                ->label('Tipo de Alerta')
                ->options([
                    'info' => 'ℹ️ Informação (Azul)',
                    'success' => '✅ Sucesso (Verde)',
                    'warning' => '⚠️ Aviso (Amarelo)',
                    'danger' => '⛔ Perigo (Vermelho)',
                ])
                ->default('info')
                ->required(),
            
            TextInput::make('title')
                ->label('Título')
                ->placeholder('Ex: Atenção!')
                ->columnSpanFull(),
            
            Textarea::make('content')
                ->label('Mensagem')
                ->required()
                ->rows(3)
                ->placeholder('Digite a mensagem do alerta...')
                ->columnSpanFull(),
        ];
    }
}

