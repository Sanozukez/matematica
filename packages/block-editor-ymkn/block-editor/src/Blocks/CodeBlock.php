<?php

namespace Ymkn\BlockEditor\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

/**
 * Bloco de Código
 * 
 * Exibição de blocos de código com syntax highlighting.
 * Suporta múltiplas linguagens de programação.
 */
class CodeBlock extends AbstractBlock
{
    protected string $name = 'code';
    protected string $label = '💻 Código';
    protected string $icon = 'heroicon-o-code-bracket';

    public function make(): Block
    {
        return $this->createBlock();
    }

    protected function getSchema(): array
    {
        return [
            Select::make('language')
                ->label('Linguagem')
                ->options([
                    'javascript' => 'JavaScript',
                    'python' => 'Python',
                    'php' => 'PHP',
                    'html' => 'HTML',
                    'css' => 'CSS',
                    'sql' => 'SQL',
                    'bash' => 'Bash',
                    'json' => 'JSON',
                    'plaintext' => 'Texto Simples',
                ])
                ->default('python')
                ->searchable(),
            
            Textarea::make('content')
                ->label('Código')
                ->required()
                ->rows(8)
                ->placeholder('Digite ou cole o código aqui...')
                ->columnSpanFull(),
            
            Textarea::make('caption')
                ->label('Título/Legenda')
                ->placeholder('Ex: Exemplo de função recursiva')
                ->rows(2)
                ->columnSpanFull(),
        ];
    }
}

