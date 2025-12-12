<?php

namespace Lafily\BlockEditor\Blocks;

use Filament\Forms\Components\Builder\Block;

/**
 * Interface para Blocos de Conteúdo de Lições
 * 
 * Define o contrato que todos os blocos devem implementar.
 * Cada bloco é responsável por sua própria definição de schema no Filament Builder.
 */
interface BlockContract
{
    /**
     * Retorna o Block do Filament configurado
     * 
     * @return Block
     */
    public function make(): Block;

    /**
     * Retorna o identificador único do bloco
     * 
     * @return string
     */
    public function getName(): string;

    /**
     * Retorna o label/título do bloco
     * 
     * @return string
     */
    public function getLabel(): string;

    /**
     * Retorna o ícone do bloco
     * 
     * @return string
     */
    public function getIcon(): string;
}
