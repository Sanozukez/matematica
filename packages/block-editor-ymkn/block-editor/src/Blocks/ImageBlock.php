<?php

namespace Ymkn\BlockEditor\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

/**
 * Bloco de Imagem
 * 
 * Upload e exibição de imagens com configurações de:
 * - Alt text (acessibilidade)
 * - Legenda
 * - Alinhamento
 * - Editor de imagem integrado
 */
class ImageBlock extends AbstractBlock
{
    protected string $name = 'image';
    protected string $label = '🖼️ Imagem';
    protected string $icon = 'heroicon-o-photo';

    public function make(): Block
    {
        return $this->createBlock()->columns(2);
    }

    protected function getSchema(): array
    {
        return [
            FileUpload::make('file')
                ->label('Imagem')
                ->image()
                ->directory('lessons/images')
                ->disk('public')
                ->maxSize(10240)
                ->imageEditor()
                ->imageEditorAspectRatios([
                    null,
                    '16:9',
                    '4:3',
                    '1:1',
                ])
                ->required()
                ->columnSpanFull(),
            
            TextInput::make('alt')
                ->label('Texto Alternativo (Alt)')
                ->placeholder('Descrição da imagem para acessibilidade')
                ->helperText('Importante para SEO e acessibilidade'),
            
            TextInput::make('caption')
                ->label('Legenda')
                ->placeholder('Legenda opcional da imagem'),
            
            Select::make('alignment')
                ->label('Alinhamento')
                ->options([
                    'left' => 'Esquerda',
                    'center' => 'Centro',
                    'right' => 'Direita',
                    'wide' => 'Largura Total',
                ])
                ->default('center'),
        ];
    }
}

