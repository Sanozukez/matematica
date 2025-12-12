<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Builder;

/**
 * Builder customizado que garante que o state sempre seja array
 * Resolve o erro: Argument #1 ($itemData) must be of type array, string given
 */
class SafeBuilder extends Builder
{
    protected function setUp(): void
    {
        parent::setUp();

        // Garantir que o state inicial seja sempre array
        $this->default([]);
        
        // Interceptar e corrigir o state ao hidratar
        $this->afterStateHydrated(function (SafeBuilder $component, $state): void {
            if (!is_array($state)) {
                $component->state([]);
            }
        });
        
        // Garantir que sempre salve como array
        $this->dehydrateStateUsing(function ($state) {
            if (!is_array($state)) {
                return [];
            }
            return $state;
        });
    }

    public function getState(): mixed
    {
        $state = parent::getState();
        
        // Proteção adicional: sempre retornar array
        if (!is_array($state)) {
            return [];
        }
        
        return $state;
    }
}

