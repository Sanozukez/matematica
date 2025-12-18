<?php

namespace Ymkn\BlockEditor\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

/**
 * Bloco de Vídeo
 * 
 * Incorporação de vídeos de diferentes provedores:
 * - YouTube
 * - Vimeo
 * - Bunny.net
 */
class VideoBlock extends AbstractBlock
{
    protected string $name = 'video';
    protected string $label = '🎥 Vídeo';
    protected string $icon = 'heroicon-o-video-camera';

    public function make(): Block
    {
        return $this->createBlock();
    }

    protected function getSchema(): array
    {
        return [
            Select::make('provider')
                ->label('Provedor')
                ->options([
                    'youtube' => 'YouTube',
                    'vimeo' => 'Vimeo',
                    'bunny' => 'Bunny.net',
                ])
                ->default('youtube')
                ->required()
                ->live(),
            
            TextInput::make('url')
                ->label('URL do Vídeo')
                ->url()
                ->required()
                ->placeholder('https://youtube.com/watch?v=...')
                ->helperText(fn ($get) => match($get('provider')) {
                    'youtube' => 'Cole o link do YouTube',
                    'vimeo' => 'Cole o link do Vimeo',
                    'bunny' => 'Cole a URL do Bunny Stream',
                    default => 'Cole o link do vídeo'
                })
                ->columnSpanFull(),
            
            Textarea::make('caption')
                ->label('Descrição/Notas')
                ->rows(2)
                ->placeholder('Pontos importantes, timestamps, etc')
                ->columnSpanFull(),
        ];
    }
}

