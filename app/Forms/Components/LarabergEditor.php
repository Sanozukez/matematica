<?php

// plataforma/app/Forms/Components/LarabergEditor.php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

/**
 * Campo customizado do Filament para usar o Laraberg (Gutenberg)
 * 
 * Este componente integra o Laraberg com o Filament Forms
 */
class LarabergEditor extends Field
{
    protected string $view = 'forms.components.laraberg-editor';
    
    /**
     * Configurações do Laraberg
     */
    protected array $larabergOptions = [];
    
    /**
     * Define opções do Laraberg
     */
    public function options(array $options): static
    {
        $this->larabergOptions = array_merge($this->larabergOptions, $options);
        
        return $this;
    }
    
    /**
     * Retorna as opções do Laraberg
     */
    public function getLarabergOptions(): array
    {
        return $this->larabergOptions;
    }
    
    /**
     * Retorna o ID único do editor (gerado baseado no state path)
     */
    public function getEditorId(): string
    {
        // Usar o state path como base para o ID único
        $statePath = $this->getStatePath();
        return 'laraberg-' . str_replace('.', '-', $statePath) . '-' . substr(md5($statePath), 0, 8);
    }
}

