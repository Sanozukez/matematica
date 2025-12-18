<?php

namespace Ymkn\BlockEditor\Services;

use Filament\Forms\Components\Builder;

/**
 * Serviço de Configuração do Editor de Blocos (Lafily)
 * 
 * Responsável por:
 * - Criar e configurar o Builder do Filament
 * - Aplicar configurações padrão do editor
 * - Fornecer métodos utilitários para o editor
 * 
 * Princípio Single Responsibility: Gerenciar apenas a configuração do editor
 */
class EditorService
{
    public function __construct(
        private readonly BlockRegistry $blockRegistry
    ) {}

    /**
     * Cria um Builder configurado para lições
     * 
     * @param array $options Opções de configuração do builder
     * @return Builder
     */
    public function createBuilder(array $options = []): Builder
    {
        $builder = Builder::make('content')
            ->label($options['label'] ?? '')
            ->blocks($this->blockRegistry->getFilamentBlocks())
            ->blockNumbers(false)
            ->addActionLabel($options['addActionLabel'] ?? '➕ Adicionar Bloco')
            ->collapsible($options['collapsible'] ?? true)
            ->cloneable($options['cloneable'] ?? true)
            ->reorderable($options['reorderable'] ?? true)
            ->columnSpanFull()
            ->minItems($options['minItems'] ?? 0);

        // Configurar ação de delete com confirmação
        if ($options['confirmDelete'] ?? true) {
            $builder->deleteAction(
                fn ($action) => $action->requiresConfirmation()
            );
        }

        return $builder;
    }

    /**
     * Cria um Builder com apenas blocos específicos
     * 
     * @param string[] $blockNames
     * @param array $options
     * @return Builder
     */
    public function createBuilderWithBlocks(array $blockNames, array $options = []): Builder
    {
        $builder = Builder::make('content')
            ->label($options['label'] ?? '')
            ->blocks($this->blockRegistry->only($blockNames))
            ->blockNumbers(false)
            ->addActionLabel($options['addActionLabel'] ?? '➕ Adicionar Bloco')
            ->collapsible($options['collapsible'] ?? true)
            ->cloneable($options['cloneable'] ?? true)
            ->reorderable($options['reorderable'] ?? true)
            ->columnSpanFull()
            ->minItems($options['minItems'] ?? 0);

        if ($options['confirmDelete'] ?? true) {
            $builder->deleteAction(
                fn ($action) => $action->requiresConfirmation()
            );
        }

        return $builder;
    }

    /**
     * Retorna o registro de blocos
     */
    public function getBlockRegistry(): BlockRegistry
    {
        return $this->blockRegistry;
    }

    /**
     * Valida se o conteúdo de uma lição está estruturado corretamente
     * 
     * @param array $content
     * @return bool
     */
    public function validateContent(array $content): bool
    {
        if (empty($content)) {
            return true; // Conteúdo vazio é válido
        }

        foreach ($content as $block) {
            // Cada bloco deve ter 'type' e 'data'
            if (!isset($block['type']) || !isset($block['data'])) {
                return false;
            }

            // O tipo deve existir no registro
            if (!$this->blockRegistry->get($block['type'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Retorna estatísticas do conteúdo
     * 
     * @param array $content
     * @return array
     */
    public function getContentStats(array $content): array
    {
        $stats = [
            'total_blocks' => count($content),
            'blocks_by_type' => [],
            'has_media' => false,
            'has_code' => false,
            'has_latex' => false,
        ];

        foreach ($content as $block) {
            $type = $block['type'] ?? 'unknown';
            
            if (!isset($stats['blocks_by_type'][$type])) {
                $stats['blocks_by_type'][$type] = 0;
            }
            $stats['blocks_by_type'][$type]++;

            // Detectar tipos especiais
            if (in_array($type, ['image', 'video'])) {
                $stats['has_media'] = true;
            }
            if ($type === 'code') {
                $stats['has_code'] = true;
            }
            if ($type === 'latex') {
                $stats['has_latex'] = true;
            }
        }

        return $stats;
    }
}

