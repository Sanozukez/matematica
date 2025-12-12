{{-- plataforma/resources/views/forms/components/laraberg-editor.blade.php --}}

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    @php
        $editorId = $field->getEditorId();
        $statePath = $getStatePath();
        $options = $field->getLarabergOptions();
        
        // Converter content para JSON string (Laraberg espera string)
        $content = $getState();
        if (is_array($content)) {
            $content = json_encode($content);
        } elseif (is_string($content)) {
            // Já é string, manter
        } else {
            $content = '';
        }
    @endphp

    <div 
        x-data="{ 
            editorId: '{{ $editorId }}',
            initialized: false
        }"
        x-init="
            // Aguardar DOM e scripts carregarem
            setTimeout(() => {
                if (typeof Laraberg !== 'undefined' && typeof wp !== 'undefined' && !initialized) {
                    const textarea = document.getElementById('{{ $editorId }}');
                    if (textarea) {
                        // Configurar blocos permitidos ANTES de inicializar
                        // Remover todos os embeds exceto YouTube
                        if (wp.blocks && wp.blocks.getBlockTypes) {
                            const blockTypes = wp.blocks.getBlockTypes();
                            
                            // Desabilitar todos os embeds
                            blockTypes.forEach(block => {
                                if (block.name && block.name.startsWith('core/embed') && block.name !== 'core/embed/youtube') {
                                    wp.blocks.unregisterBlockType(block.name);
                                }
                            });
                            
                            // Garantir que YouTube está disponível
                            if (!wp.blocks.getBlockType('core/embed/youtube')) {
                                // Registrar apenas YouTube se não existir
                                wp.blocks.registerBlockType('core/embed/youtube', {
                                    name: 'core/embed/youtube',
                                    title: 'YouTube',
                                    category: 'embed',
                                    icon: 'video-alt3',
                                    supports: {
                                        align: true
                                    }
                                });
                            }
                        }
                        
                        // Inicializar Laraberg
                        Laraberg.init('{{ $editorId }}', {
                            height: '600px',
                            ...@js($options)
                        });
                        
                        // Sincronizar mudanças com Livewire
                        textarea.addEventListener('input', function() {
                            const content = this.value;
                            console.log('[Laraberg] sync -> Livewire', { 
                                id: '{{ $editorId }}', 
                                length: content?.length || 0,
                                preview: content?.substring(0, 100) || ''
                            });
                            
                            try {
                                @this.set('{{ $statePath }}', content);
                                console.log('[Laraberg] Livewire set executado com sucesso');
                            } catch (e) {
                                console.error('[Laraberg] Erro ao sincronizar com Livewire:', e);
                            }
                        });
                        
                        // Adicionar listener para mudanças do Gutenberg
                        if (typeof wp !== 'undefined' && wp.data) {
                            wp.data.subscribe(function() {
                                const content = textarea.value;
                                console.log('[Laraberg] Gutenberg mudou, conteúdo atual:', {
                                    length: content?.length || 0,
                                    preview: content?.substring(0, 100) || ''
                                });
                            });
                        }
                        
                        initialized = true;
                    }
                } else if (!initialized) {
                    console.warn('Laraberg ou WordPress não está disponível ainda');
                }
            }, 1500);
        "
    >
        <textarea 
            id="{{ $editorId }}"
            name="{{ $statePath }}"
            wire:model="{{ $statePath }}"
            style="display: none;"
        >{{ $content }}</textarea>
    </div>
</x-dynamic-component>
