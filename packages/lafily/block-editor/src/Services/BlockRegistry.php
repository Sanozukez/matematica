<?php

namespace Lafily\BlockEditor\Services;

use Lafily\BlockEditor\Blocks\BlockContract;
use Lafily\BlockEditor\Blocks\ParagraphBlock;
use Lafily\BlockEditor\Blocks\HeadingBlock;
use Lafily\BlockEditor\Blocks\ImageBlock;
use Lafily\BlockEditor\Blocks\VideoBlock;
use Lafily\BlockEditor\Blocks\CodeBlock;
use Lafily\BlockEditor\Blocks\QuoteBlock;
use Lafily\BlockEditor\Blocks\AlertBlock;
use Lafily\BlockEditor\Blocks\ListBlock;
use Lafily\BlockEditor\Blocks\LatexBlock;
use Lafily\BlockEditor\Blocks\DividerBlock;
use Lafily\BlockEditor\Blocks\TableBlock;

/**
 * Registro Central de Blocos de Lições
 * 
 * Responsável por:
 * - Registrar todos os blocos disponíveis
 * - Fornecer blocos para o Builder do Filament
 * - Permitir extensão fácil com novos blocos
 * 
 * Princípio Single Responsibility: Gerenciar apenas o registro de blocos
 */
class BlockRegistry
{
    /**
     * @var BlockContract[]
     */
    private array $blocks = [];

    public function __construct()
    {
        $this->registerDefaultBlocks();
    }

    /**
     * Registra os blocos padrão do sistema
     */
    private function registerDefaultBlocks(): void
    {
        $this->register(new ParagraphBlock());
        $this->register(new HeadingBlock());
        $this->register(new ImageBlock());
        $this->register(new VideoBlock());
        $this->register(new CodeBlock());
        $this->register(new QuoteBlock());
        $this->register(new AlertBlock());
        $this->register(new ListBlock());
        $this->register(new LatexBlock());
        $this->register(new DividerBlock());
        $this->register(new TableBlock());
    }

    /**
     * Registra um novo bloco
     */
    public function register(BlockContract $block): self
    {
        $this->blocks[$block->getName()] = $block;
        return $this;
    }

    /**
     * Remove um bloco do registro
     */
    public function unregister(string $name): self
    {
        unset($this->blocks[$name]);
        return $this;
    }

    /**
     * Obtém um bloco específico pelo nome
     */
    public function get(string $name): ?BlockContract
    {
        return $this->blocks[$name] ?? null;
    }

    /**
     * Obtém todos os blocos registrados
     * 
     * @return BlockContract[]
     */
    public function all(): array
    {
        return $this->blocks;
    }

    /**
     * Retorna array de Blocks do Filament prontos para uso no Builder
     * 
     * @return \Filament\Forms\Components\Builder\Block[]
     */
    public function getFilamentBlocks(): array
    {
        return array_map(
            fn (BlockContract $block) => $block->make(),
            $this->blocks
        );
    }

    /**
     * Retorna apenas blocos específicos (útil para contextos diferentes)
     * 
     * @param string[] $names
     * @return \Filament\Forms\Components\Builder\Block[]
     */
    public function only(array $names): array
    {
        $filtered = array_filter(
            $this->blocks,
            fn (BlockContract $block) => in_array($block->getName(), $names)
        );

        return array_map(
            fn (BlockContract $block) => $block->make(),
            $filtered
        );
    }

    /**
     * Retorna todos os blocos exceto os especificados
     * 
     * @param string[] $names
     * @return \Filament\Forms\Components\Builder\Block[]
     */
    public function except(array $names): array
    {
        $filtered = array_filter(
            $this->blocks,
            fn (BlockContract $block) => !in_array($block->getName(), $names)
        );

        return array_map(
            fn (BlockContract $block) => $block->make(),
            $filtered
        );
    }
}
