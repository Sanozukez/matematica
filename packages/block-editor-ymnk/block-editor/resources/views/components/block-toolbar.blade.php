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
    x-data="{ 
        showColors: false,
        showAlign: false,
        showHeadingLevel: false,
        showMore: false,
        showColumnMenu: false,
        showLinkInput: false,
        linkUrl: ''
    }"
>
    {{-- Seção 1: CONTROLES UNIVERSAIS (todos os blocos) --}}
    <div class="block-toolbar-section">
        {{-- Ícone do Tipo do Bloco (apenas ícone) --}}
        <button class="block-toolbar-btn" title="Alterar tipo de bloco" @click="showBlockTransformMenu(block.id, $event)">
            <div class="block-toolbar-icon" x-html="blockTypes.find(t => t.type === block.type)?.icon || ''"></div>
        </button>

        {{-- Drag Handle (Material Icons) --}}
        <button 
            class="block-toolbar-btn" 
            title="Arrastar para reordenar"
            draggable="true"
            @dragstart="handleDragStart($event, block.id)"
            @dragend="handleDragEnd($event)"
            style="cursor: grab;"
        >
            <span class="material-icons">drag_indicator</span>
        </button>

        {{-- Move Up/Down (chevrons em coluna) --}}
        <div class="block-toolbar-move-vertical">
            <button class="block-toolbar-btn-mini" title="Mover para cima" 
                    @click="moveBlockUp(block.id)"
                    :disabled="blocks.findIndex(b => b.id === block.id) === 0">
                <span class="material-icons">expand_less</span>
            </button>
            <button class="block-toolbar-btn-mini" title="Mover para baixo"
                    @click="moveBlockDown(block.id)"
                    :disabled="blocks.findIndex(b => b.id === block.id) === blocks.length - 1">
                <span class="material-icons">expand_more</span>
            </button>
        </div>
    </div>

    <div class="block-toolbar-divider"></div>

    {{-- Seção 2: OPÇÕES ESPECÍFICAS DO TIPO DE BLOCO --}}

    {{-- COLUMNS: Seleção de quantidade de colunas --}}
    <template x-if="block.type === 'columns'">
        <div class="block-toolbar-section">
            <div class="block-toolbar-dropdown">
                <button class="block-toolbar-btn" title="Número de colunas"
                        @click="showColumnMenu = !showColumnMenu"
                        @click.outside="showColumnMenu = false">
                    <span class="material-icons">view_column</span>
                </button>
                <div x-show="showColumnMenu" class="block-toolbar-dropdown-menu">
                    <template x-for="n in [2,3,4]" :key="n">
                        <button class="block-toolbar-dropdown-item" @click="setColumnCount(block.id, n); showColumnMenu=false" :class="{ 'active': (block.attributes?.columnCount || 2) === n }">
                            <span class="material-icons">view_column</span>
                            <span x-text="n + ' colunas'"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </template>
    
    {{-- PARAGRAPH: Alinhamento + Formatação --}}
    <template x-if="block.type === 'paragraph'">
        <div class="block-toolbar-section">
            {{-- Alinhamento (Dropdown) --}}
            <div class="block-toolbar-dropdown">
                <button class="block-toolbar-btn" title="Alinhar texto"
                        @click="showAlign = !showAlign"
                        @click.outside="showAlign = false">
                    <span class="material-icons">format_align_left</span>
                </button>
                
                <div x-show="showAlign" class="block-toolbar-dropdown-menu">
                    <button class="block-toolbar-dropdown-item" @click="applyAlignment(block.id, 'left'); showAlign = false">
                        <span class="material-icons">format_align_left</span>
                        <span>Alinhar à esquerda</span>
                    </button>
                    <button class="block-toolbar-dropdown-item" @click="applyAlignment(block.id, 'center'); showAlign = false">
                        <span class="material-icons">format_align_center</span>
                        <span>Centralizar</span>
                    </button>
                    <button class="block-toolbar-dropdown-item" @click="applyAlignment(block.id, 'right'); showAlign = false">
                        <span class="material-icons">format_align_right</span>
                        <span>Alinhar à direita</span>
                    </button>
                    <button class="block-toolbar-dropdown-item" @click="applyAlignment(block.id, 'justify'); showAlign = false">
                        <span class="material-icons">format_align_justify</span>
                        <span>Justificar</span>
                    </button>
                </div>
            </div>
            
            <div class="block-toolbar-divider"></div>
            
            {{-- Negrito --}}
            <button class="block-toolbar-btn" title="Negrito (Ctrl+B)"
                    @click="applyFormatting(block.id, 'bold')">
                <span class="material-icons">format_bold</span>
            </button>
            
            {{-- Itálico --}}
            <button class="block-toolbar-btn" title="Itálico (Ctrl+I)"
                    @click="applyFormatting(block.id, 'italic')">
                <span class="material-icons">format_italic</span>
            </button>
            
            {{-- Link --}}
            <div class="block-toolbar-dropdown">
                <button class="block-toolbar-btn" title="Inserir link (Ctrl+K)"
                        @click="showLinkInput = !showLinkInput"
                        @click.outside="showLinkInput = false">
                    <span class="material-icons">link</span>
                </button>
                
                <div x-show="showLinkInput" class="block-toolbar-dropdown-menu" style="min-width: 280px;">
                    <input type="url" 
                           x-model="linkUrl" 
                           placeholder="https://exemplo.com"
                           @keydown.enter="insertLink(block.id, linkUrl); linkUrl = ''; showLinkInput = false"
                           style="width: 100%; padding: 8px; background: #2C2C2C; border: 1px solid #3C3C3C; border-radius: 4px; color: #E0E0E0; font-size: 13px; margin-bottom: 8px;">
                    <button class="block-toolbar-dropdown-item" @click="insertLink(block.id, linkUrl); linkUrl = ''; showLinkInput = false">
                        <span class="material-icons">done</span>
                        <span>Inserir link</span>
                    </button>
                </div>
            </div>
        </div>
    </template>

    {{-- HEADING: Nível H + Alinhamento + Formatação --}}
    <template x-if="block.type === 'heading'">
        <div class="block-toolbar-section">
            {{-- Seletor de nível H1-H6 (Dropdown) --}}
            <div class="block-toolbar-dropdown">
                <button class="block-toolbar-btn block-toolbar-heading-level" title="Alterar nível do título"
                        @click="showHeadingLevel = !showHeadingLevel"
                        @click.outside="showHeadingLevel = false">
                    <span x-text="'H' + (block.attributes.level || 2)"></span>
                    <span class="material-icons" style="font-size: 14px;">expand_more</span>
                </button>
                
                <div x-show="showHeadingLevel" class="block-toolbar-dropdown-menu">
                    <template x-for="level in [1,2,3,4,5,6]" :key="level">
                        <button class="block-toolbar-dropdown-item" 
                                @click="updateBlockAttributes(block.id, { level }); showHeadingLevel = false">
                            <span x-text="'H' + level" style="font-weight: bold; font-size: 14px;"></span>
                            <span x-text="'Título ' + level"></span>
                        </button>
                    </template>
                </div>
            </div>
            
            <div class="block-toolbar-divider"></div>
            
            {{-- Alinhamento (Dropdown) --}}
            <div class="block-toolbar-dropdown">
                <button class="block-toolbar-btn" title="Alinhar título"
                        @click="showAlign = !showAlign"
                        @click.outside="showAlign = false">
                    <span class="material-icons">format_align_left</span>
                </button>
                
                <div x-show="showAlign" class="block-toolbar-dropdown-menu">
                    <button class="block-toolbar-dropdown-item" @click="applyAlignment(block.id, 'left'); showAlign = false">
                        <span class="material-icons">format_align_left</span>
                        <span>Alinhar à esquerda</span>
                    </button>
                    <button class="block-toolbar-dropdown-item" @click="applyAlignment(block.id, 'center'); showAlign = false">
                        <span class="material-icons">format_align_center</span>
                        <span>Centralizar</span>
                    </button>
                    <button class="block-toolbar-dropdown-item" @click="applyAlignment(block.id, 'right'); showAlign = false">
                        <span class="material-icons">format_align_right</span>
                        <span>Alinhar à direita</span>
                    </button>
                </div>
            </div>
            
            <div class="block-toolbar-divider"></div>
            
            {{-- Negrito --}}
            <button class="block-toolbar-btn" title="Negrito"
                    @click="applyFormatting(block.id, 'bold')">
                <span class="material-icons">format_bold</span>
            </button>
            
            {{-- Itálico --}}
            <button class="block-toolbar-btn" title="Itálico"
                    @click="applyFormatting(block.id, 'italic')">
                <span class="material-icons">format_italic</span>
            </button>
            
            {{-- Link --}}
            <div class="block-toolbar-dropdown">
                <button class="block-toolbar-btn" title="Inserir link"
                        @click="showLinkInput = !showLinkInput"
                        @click.outside="showLinkInput = false">
                    <span class="material-icons">link</span>
                </button>
                
                <div x-show="showLinkInput" class="block-toolbar-dropdown-menu" style="min-width: 280px;">
                    <input type="url" 
                           x-model="linkUrl" 
                           placeholder="https://exemplo.com"
                           @keydown.enter="insertLink(block.id, linkUrl); linkUrl = ''; showLinkInput = false"
                           style="width: 100%; padding: 8px; background: #2C2C2C; border: 1px solid #3C3C3C; border-radius: 4px; color: #E0E0E0; font-size: 13px; margin-bottom: 8px;">
                    <button class="block-toolbar-dropdown-item" @click="insertLink(block.id, linkUrl); linkUrl = ''; showLinkInput = false">
                        <span class="material-icons">done</span>
                        <span>Inserir link</span>
                    </button>
                </div>
            </div>
        </div>
    </template>

    {{-- QUOTE: Alinhamento --}}
    <template x-if="block.type === 'quote'">
        <div class="block-toolbar-section">
            {{-- Alinhamento (Dropdown) --}}
            <div class="block-toolbar-dropdown">
                <button class="block-toolbar-btn" title="Alinhar citação"
                        @click="showAlign = !showAlign"
                        @click.outside="showAlign = false">
                    <span class="material-icons">format_align_left</span>
                </button>
                
                <div x-show="showAlign" class="block-toolbar-dropdown-menu">
                    <button class="block-toolbar-dropdown-item" @click="applyAlignment(block.id, 'left'); showAlign = false">
                        <span class="material-icons">format_align_left</span>
                        <span>Alinhar à esquerda</span>
                    </button>
                    <button class="block-toolbar-dropdown-item" @click="applyAlignment(block.id, 'center'); showAlign = false">
                        <span class="material-icons">format_align_center</span>
                        <span>Centralizar</span>
                    </button>
                    <button class="block-toolbar-dropdown-item" @click="applyAlignment(block.id, 'right'); showAlign = false">
                        <span class="material-icons">format_align_right</span>
                        <span>Alinhar à direita</span>
                    </button>
                </div>
            </div>
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

    {{-- IMAGE: Alinhamento + Largura --}}
    <template x-if="block.type === 'image'">
        <div class="block-toolbar-section">
            {{-- Alinhamento --}}
            <div class="block-toolbar-dropdown">
                <button class="block-toolbar-btn" title="Alinhar imagem"
                        @click="showAlign = !showAlign"
                        @click.outside="showAlign = false">
                    <span class="material-icons">format_align_left</span>
                </button>
                
                <div x-show="showAlign" class="block-toolbar-dropdown-menu">
                    <button class="block-toolbar-dropdown-item" @click="applyAlignment(block.id, 'left'); showAlign = false">
                        <span class="material-icons">format_align_left</span>
                        <span>Alinhar à esquerda</span>
                    </button>
                    <button class="block-toolbar-dropdown-item" @click="applyAlignment(block.id, 'center'); showAlign = false">
                        <span class="material-icons">format_align_center</span>
                        <span>Centralizar</span>
                    </button>
                    <button class="block-toolbar-dropdown-item" @click="applyAlignment(block.id, 'right'); showAlign = false">
                        <span class="material-icons">format_align_right</span>
                        <span>Alinhar à direita</span>
                    </button>
                </div>
            </div>
            
            <div class="block-toolbar-divider"></div>
            
            {{-- Largura --}}
            <button class="block-toolbar-btn" title="Configurar largura" disabled>
                <span class="material-icons">photo_size_select_large</span>
            </button>
            
            {{-- Link externo --}}
            <button class="block-toolbar-btn" title="Adicionar link" disabled>
                <span class="material-icons">link</span>
            </button>
        </div>
    </template>

    {{-- VIDEO: Controles --}}
    <template x-if="block.type === 'video'">
        <div class="block-toolbar-section">
            <button class="block-toolbar-btn" title="Alinhamento" disabled>
                <span class="material-icons">format_align_center</span>
            </button>
            
            <div class="block-toolbar-divider"></div>
            
            <button class="block-toolbar-btn" title="Proporção" disabled>
                <span class="material-icons">aspect_ratio</span>
            </button>
        </div>
    </template>

    {{-- ALERT: Tipo de alerta --}}
    <template x-if="block.type === 'alert'">
        <div class="block-toolbar-section">
            <button class="block-toolbar-btn" title="Tipo de alerta (info, warning, error, success)" disabled>
                <span class="material-icons">info</span>
            </button>
        </div>
    </template>

    {{-- LIST: Tipo de lista --}}
    <template x-if="block.type === 'list'">
        <div class="block-toolbar-section">
            <button class="block-toolbar-btn" title="Lista ordenada">
                <span class="material-icons">format_list_numbered</span>
            </button>
            
            <button class="block-toolbar-btn" title="Lista com marcadores">
                <span class="material-icons">format_list_bulleted</span>
            </button>
            
            <div class="block-toolbar-divider"></div>
            
            <button class="block-toolbar-btn" title="Aumentar indentação" disabled>
                <span class="material-icons">format_indent_increase</span>
            </button>
            
            <button class="block-toolbar-btn" title="Diminuir indentação" disabled>
                <span class="material-icons">format_indent_decrease</span>
            </button>
        </div>
    </template>

    {{-- LATEX: Modo inline/block --}}
    <template x-if="block.type === 'latex'">
        <div class="block-toolbar-section">
            <button class="block-toolbar-btn" title="Modo inline ($...$ )" disabled>
                <span class="material-icons">short_text</span>
            </button>
            
            <button class="block-toolbar-btn" title="Modo block ($$...$$)" disabled>
                <span class="material-icons">subject</span>
            </button>
            
            <div class="block-toolbar-divider"></div>
            
            <button class="block-toolbar-btn" title="Visualizar renderização" disabled>
                <span class="material-icons">visibility</span>
            </button>
        </div>
    </template>

    {{-- DIVIDER: Estilo --}}
    <template x-if="block.type === 'divider'">
        <div class="block-toolbar-section">
            <button class="block-toolbar-btn" title="Estilo do divisor" disabled>
                <span class="material-icons">horizontal_rule</span>
            </button>
        </div>
    </template>

    {{-- TABLE: Controles de tabela --}}
    <template x-if="block.type === 'table'">
        <div class="block-toolbar-section">
            <button class="block-toolbar-btn" title="Adicionar linha" disabled>
                <span class="material-icons">add</span>
            </button>
            
            <button class="block-toolbar-btn" title="Remover linha" disabled>
                <span class="material-icons">remove</span>
            </button>
            
            <div class="block-toolbar-divider"></div>
            
            <button class="block-toolbar-btn" title="Adicionar coluna" disabled>
                <span class="material-icons">view_column</span>
            </button>
            
            <button class="block-toolbar-btn" title="Remover coluna" disabled>
                <span class="material-icons">view_column</span>
            </button>
            
            <div class="block-toolbar-divider"></div>
            
            <button class="block-toolbar-btn" title="Cabeçalho" disabled>
                <span class="material-icons">table_rows</span>
            </button>
        </div>
    </template>

    {{-- Seção 3: SELETOR DE CORES (blocos de texto) + MORE OPTIONS --}}
    <template x-if="['paragraph', 'heading', 'quote'].includes(block.type)">
        <div class="block-toolbar-section">
            <div class="block-toolbar-divider"></div>
            @include('block-editor-ymnk::components.toolbar-color-picker-grid')
        </div>
    </template>
    
    <div class="block-toolbar-divider"></div>
    
    {{-- More Options (todos os blocos) --}}
    <div class="block-toolbar-section">
        <div class="block-toolbar-dropdown">
            <button class="block-toolbar-btn" title="Mais opções"
                    @click="showMore = !showMore"
                    @click.outside="showMore = false">
                <span class="material-icons">more_vert</span>
            </button>
            
            <div x-show="showMore" class="block-toolbar-dropdown-menu">
                <button class="block-toolbar-dropdown-item" @click="duplicateBlock(block.id); showMore = false">
                    <span class="material-icons">content_copy</span>
                    <span>Duplicar bloco</span>
                </button>
                <button class="block-toolbar-dropdown-item" @click="addBlock('paragraph', blocks.findIndex(b => b.id === block.id)); showMore = false">
                    <span class="material-icons">add_circle_outline</span>
                    <span>Inserir bloco antes</span>
                </button>
                <button class="block-toolbar-dropdown-item" @click="addBlock('paragraph', blocks.findIndex(b => b.id === block.id) + 1); showMore = false">
                    <span class="material-icons">add_circle_outline</span>
                    <span>Inserir bloco depois</span>
                </button>
                <div style="height: 1px; background: #3C3C3C; margin: 4px 0;"></div>
                <button class="block-toolbar-dropdown-item" @click="removeBlock(block.id); showMore = false" style="color: #FF6B6B;">
                    <span class="material-icons">delete_outline</span>
                    <span>Excluir bloco</span>
                </button>
            </div>
        </div>
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
