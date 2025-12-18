<?php

namespace Ymkn\BlockEditor\Blocks;

use Filament\Forms\Components\Builder\Block;

/**
 * Classe base abstrata para Blocos de Conteúdo
 * 
 * Implementa funcionalidades comuns a todos os blocos,
 * reduzindo duplicação de código.
 */
abstract class AbstractBlock implements BlockContract
{
    /**
     * Nome/identificador do bloco
     */
    protected string $name;

    /**
     * Label exibido na interface
     */
    protected string $label;

    /**
     * Ícone Heroicon
     */
    protected string $icon;

    /**
     * Retorna o Block do Filament configurado com schema
     */
    abstract public function make(): Block;

    /**
     * Retorna o schema de campos do bloco
     */
    abstract protected function getSchema(): array;

    /**
     * Cria o Block base com configurações comuns
     */
    protected function createBlock(): Block
    {
        return Block::make($this->name)
            ->label($this->label)
            ->icon($this->icon)
            ->schema($this->getSchema());
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }
}

