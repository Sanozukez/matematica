{{-- Block Toolbar Universal (WordPress Style com Material Icons) --}}
{{-- Aparece flutuante acima de qualquer bloco focado --}}

{{-- Material Icons CDN --}}
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div 
    x-show="focusedBlockId === block.id"
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="block-toolbar-universal"
>
    {{-- Seção 1: CONTROLES UNIVERSAIS (todos os blocos) --}}
    <div class="block-toolbar-section">
        {{-- Ícone do Tipo do Bloco (apenas ícone) --}}
        <button class="block-toolbar-btn" title="Alterar tipo de bloco">
            <div class="block-toolbar-icon" x-html="blockTypes.find(t => t.type === block.type)?.icon || ''"></div>
        </button>

        {{-- Drag Handle (Material Icons) --}}
        <button class="block-toolbar-btn" title="Arrastar para reordenar" disabled>
            <span class="material-icons">drag_indicator</span>
        </button>

        {{-- Move Up/Down (chevrons em coluna) --}}
        <div class="block-toolbar-move-vertical">
            <button class="block-toolbar-btn-mini" title="Mover para cima" disabled>
                <span class="material-icons">expand_less</span>
            </button>
            <button class="block-toolbar-btn-mini" title="Mover para baixo" disabled>
                <span class="material-icons">expand_more</span>
            </button>
        </div>
    </div>

    <div class="block-toolbar-divider"></div>

    {{-- Seção 2: OPÇÕES ESPECÍFICAS DO TIPO DE BLOCO --}}
    
    {{-- PARAGRAPH: Alinhamento + Formatação --}}
    <template x-if="block.type === 'paragraph'">
        <div class="block-toolbar-section">
            {{-- Alinhamento --}}
            <button class="block-toolbar-btn" title="Alinhar texto" disabled>
                <span class="material-icons">format_align_left</span>
            </button>
            
            <div class="block-toolbar-divider"></div>
            
            {{-- Negrito --}}
            <button class="block-toolbar-btn" title="Negrito (Ctrl+B)" disabled>
                <span class="material-icons">format_bold</span>
            </button>
            
            {{-- Itálico --}}
            <button class="block-toolbar-btn" title="Itálico (Ctrl+I)" disabled>
                <span class="material-icons">format_italic</span>
            </button>
            
            {{-- Link --}}
            <button class="block-toolbar-btn" title="Inserir link (Ctrl+K)" disabled>
                <span class="material-icons">link</span>
            </button>
            
            {{-- Mais formatações (dropdown) --}}
            <button class="block-toolbar-btn" title="Mais opções de formatação" disabled>
                <span class="material-icons">expand_more</span>
            </button>
        </div>
    </template>

    {{-- HEADING: Nível H + Alinhamento + Formatação --}}
    <template x-if="block.type === 'heading'">
        <div class="block-toolbar-section">
            {{-- Seletor de nível H1-H6 --}}
            <button class="block-toolbar-btn block-toolbar-heading-level" title="Alterar nível do título">
                <span x-text="'H' + (block.attributes.level || 2)"></span>
                <span class="material-icons" style="font-size: 14px;">expand_more</span>
            </button>
            
            <div class="block-toolbar-divider"></div>
            
            {{-- Alinhamento --}}
            <button class="block-toolbar-btn" title="Alinhar título" disabled>
                <span class="material-icons">format_align_left</span>
            </button>
            
            <div class="block-toolbar-divider"></div>
            
            {{-- Negrito --}}
            <button class="block-toolbar-btn" title="Negrito" disabled>
                <span class="material-icons">format_bold</span>
            </button>
            
            {{-- Itálico --}}
            <button class="block-toolbar-btn" title="Itálico" disabled>
                <span class="material-icons">format_italic</span>
            </button>
            
            {{-- Link --}}
            <button class="block-toolbar-btn" title="Inserir link" disabled>
                <span class="material-icons">link</span>
            </button>
        </div>
    </template>

    {{-- QUOTE: Alinhamento --}}
    <template x-if="block.type === 'quote'">
        <div class="block-toolbar-section">
            <button class="block-toolbar-btn" title="Alinhar citação" disabled>
                <span class="material-icons">format_align_left</span>
            </button>
        </div>
    </template>

    {{-- CODE: Linguagem --}}
    <template x-if="block.type === 'code'">
        <div class="block-toolbar-section">
            <button class="block-toolbar-btn" title="Selecionar linguagem" disabled>
                <span class="material-icons">code</span>
            </button>
        </div>
    </template>

    {{-- Seção 3: MORE OPTIONS (todos os blocos) --}}
    <div class="block-toolbar-divider"></div>
    <div class="block-toolbar-section">
        <button class="block-toolbar-btn" title="Mais opções" disabled>
            <span class="material-icons">more_vert</span>
        </button>
    </div>
</div>

<style>
/* Toolbar Universal Flutuante */
.block-toolbar-universal {
    position: absolute;
    top: -54px;
    left: 0;
    background: #1E1E1E;
    border-radius: 6px;
    padding: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    z-index: 10;
}

.block-toolbar-section {
    display: flex;
    align-items: center;
    gap: 2px;
}

.block-toolbar-divider {
    width: 1px;
    height: 24px;
    background: rgba(255, 255, 255, 0.2);
    margin: 0 4px;
}

.block-toolbar-btn {
    width: 36px;
    height: 36px;
    background: transparent;
    border: none;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
    color: white;
}

.block-toolbar-btn:hover:not(:disabled) {
    background: rgba(255, 255, 255, 0.1);
}

.block-toolbar-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.block-toolbar-btn svg {
    width: 20px;
    height: 20px;
}

.block-toolbar-type-btn {
    width: auto;
    padding: 0 12px;
    gap: 6px;
    font-size: 13px;
    font-weight: 500;
}

.block-toolbar-dropdown {
    position: relative;
}
</style>
