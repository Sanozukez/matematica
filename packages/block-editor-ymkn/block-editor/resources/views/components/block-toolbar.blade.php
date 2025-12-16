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

    {{-- Seção 3: SELETOR DE CORES (blocos de texto) + MORE OPTIONS --}}
    <template x-if="['paragraph', 'heading', 'quote'].includes(block.type)">
        <div class="block-toolbar-section">
            <div class="block-toolbar-divider"></div>
            
            {{-- Seletor de Cores com Dropdown --}}
            <div class="block-toolbar-color-picker" x-data="{ showColors: false }">
                <button 
                    class="block-toolbar-btn"
                    @click="showColors = !showColors"
                    title="Cor do texto"
                >
                    <span class="material-icons">palette</span>
                </button>
                
                {{-- Grid de Cores Tailwind COMPLETO (11 tons cada) --}}
                <div 
                    x-show="showColors"
                    @click.outside="showColors = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="block-toolbar-color-grid"
                >
                    {{-- Slate --}}
                    <div class="color-row">
                        <button class="color-swatch bg-slate-50" @click="applyTextColor(block.id, 'text-slate-50'); showColors = false"></button>
                        <button class="color-swatch bg-slate-100" @click="applyTextColor(block.id, 'text-slate-100'); showColors = false"></button>
                        <button class="color-swatch bg-slate-200" @click="applyTextColor(block.id, 'text-slate-200'); showColors = false"></button>
                        <button class="color-swatch bg-slate-300" @click="applyTextColor(block.id, 'text-slate-300'); showColors = false"></button>
                        <button class="color-swatch bg-slate-400" @click="applyTextColor(block.id, 'text-slate-400'); showColors = false"></button>
                        <button class="color-swatch bg-slate-500" @click="applyTextColor(block.id, 'text-slate-500'); showColors = false"></button>
                        <button class="color-swatch bg-slate-600" @click="applyTextColor(block.id, 'text-slate-600'); showColors = false"></button>
                        <button class="color-swatch bg-slate-700" @click="applyTextColor(block.id, 'text-slate-700'); showColors = false"></button>
                        <button class="color-swatch bg-slate-800" @click="applyTextColor(block.id, 'text-slate-800'); showColors = false"></button>
                        <button class="color-swatch bg-slate-900" @click="applyTextColor(block.id, 'text-slate-900'); showColors = false"></button>
                        <button class="color-swatch bg-slate-950" @click="applyTextColor(block.id, 'text-slate-950'); showColors = false"></button>
                    </div>
                    {{-- Gray --}}
                    <div class="color-row">
                        <button class="color-swatch bg-gray-50" @click="applyTextColor(block.id, 'text-gray-50'); showColors = false"></button>
                        <button class="color-swatch bg-gray-100" @click="applyTextColor(block.id, 'text-gray-100'); showColors = false"></button>
                        <button class="color-swatch bg-gray-200" @click="applyTextColor(block.id, 'text-gray-200'); showColors = false"></button>
                        <button class="color-swatch bg-gray-300" @click="applyTextColor(block.id, 'text-gray-300'); showColors = false"></button>
                        <button class="color-swatch bg-gray-400" @click="applyTextColor(block.id, 'text-gray-400'); showColors = false"></button>
                        <button class="color-swatch bg-gray-500" @click="applyTextColor(block.id, 'text-gray-500'); showColors = false"></button>
                        <button class="color-swatch bg-gray-600" @click="applyTextColor(block.id, 'text-gray-600'); showColors = false"></button>
                        <button class="color-swatch bg-gray-700" @click="applyTextColor(block.id, 'text-gray-700'); showColors = false"></button>
                        <button class="color-swatch bg-gray-800" @click="applyTextColor(block.id, 'text-gray-800'); showColors = false"></button>
                        <button class="color-swatch bg-gray-900" @click="applyTextColor(block.id, 'text-gray-900'); showColors = false"></button>
                        <button class="color-swatch bg-gray-950" @click="applyTextColor(block.id, 'text-gray-950'); showColors = false"></button>
                    </div>
                    {{-- Zinc --}}
                    <div class="color-row">
                        <button class="color-swatch bg-zinc-50" @click="applyTextColor(block.id, 'text-zinc-50'); showColors = false"></button>
                        <button class="color-swatch bg-zinc-100" @click="applyTextColor(block.id, 'text-zinc-100'); showColors = false"></button>
                        <button class="color-swatch bg-zinc-200" @click="applyTextColor(block.id, 'text-zinc-200'); showColors = false"></button>
                        <button class="color-swatch bg-zinc-300" @click="applyTextColor(block.id, 'text-zinc-300'); showColors = false"></button>
                        <button class="color-swatch bg-zinc-400" @click="applyTextColor(block.id, 'text-zinc-400'); showColors = false"></button>
                        <button class="color-swatch bg-zinc-500" @click="applyTextColor(block.id, 'text-zinc-500'); showColors = false"></button>
                        <button class="color-swatch bg-zinc-600" @click="applyTextColor(block.id, 'text-zinc-600'); showColors = false"></button>
                        <button class="color-swatch bg-zinc-700" @click="applyTextColor(block.id, 'text-zinc-700'); showColors = false"></button>
                        <button class="color-swatch bg-zinc-800" @click="applyTextColor(block.id, 'text-zinc-800'); showColors = false"></button>
                        <button class="color-swatch bg-zinc-900" @click="applyTextColor(block.id, 'text-zinc-900'); showColors = false"></button>
                        <button class="color-swatch bg-zinc-950" @click="applyTextColor(block.id, 'text-zinc-950'); showColors = false"></button>
                    </div>
                    {{-- Neutral --}}
                    <div class="color-row">
                        <button class="color-swatch bg-neutral-50" @click="applyTextColor(block.id, 'text-neutral-50'); showColors = false"></button>
                        <button class="color-swatch bg-neutral-100" @click="applyTextColor(block.id, 'text-neutral-100'); showColors = false"></button>
                        <button class="color-swatch bg-neutral-200" @click="applyTextColor(block.id, 'text-neutral-200'); showColors = false"></button>
                        <button class="color-swatch bg-neutral-300" @click="applyTextColor(block.id, 'text-neutral-300'); showColors = false"></button>
                        <button class="color-swatch bg-neutral-400" @click="applyTextColor(block.id, 'text-neutral-400'); showColors = false"></button>
                        <button class="color-swatch bg-neutral-500" @click="applyTextColor(block.id, 'text-neutral-500'); showColors = false"></button>
                        <button class="color-swatch bg-neutral-600" @click="applyTextColor(block.id, 'text-neutral-600'); showColors = false"></button>
                        <button class="color-swatch bg-neutral-700" @click="applyTextColor(block.id, 'text-neutral-700'); showColors = false"></button>
                        <button class="color-swatch bg-neutral-800" @click="applyTextColor(block.id, 'text-neutral-800'); showColors = false"></button>
                        <button class="color-swatch bg-neutral-900" @click="applyTextColor(block.id, 'text-neutral-900'); showColors = false"></button>
                        <button class="color-swatch bg-neutral-950" @click="applyTextColor(block.id, 'text-neutral-950'); showColors = false"></button>
                    </div>
                    {{-- Stone --}}
                    <div class="color-row">
                        <button class="color-swatch bg-stone-50" @click="applyTextColor(block.id, 'text-stone-50'); showColors = false"></button>
                        <button class="color-swatch bg-stone-100" @click="applyTextColor(block.id, 'text-stone-100'); showColors = false"></button>
                        <button class="color-swatch bg-stone-200" @click="applyTextColor(block.id, 'text-stone-200'); showColors = false"></button>
                        <button class="color-swatch bg-stone-300" @click="applyTextColor(block.id, 'text-stone-300'); showColors = false"></button>
                        <button class="color-swatch bg-stone-400" @click="applyTextColor(block.id, 'text-stone-400'); showColors = false"></button>
                        <button class="color-swatch bg-stone-500" @click="applyTextColor(block.id, 'text-stone-500'); showColors = false"></button>
                        <button class="color-swatch bg-stone-600" @click="applyTextColor(block.id, 'text-stone-600'); showColors = false"></button>
                        <button class="color-swatch bg-stone-700" @click="applyTextColor(block.id, 'text-stone-700'); showColors = false"></button>
                        <button class="color-swatch bg-stone-800" @click="applyTextColor(block.id, 'text-stone-800'); showColors = false"></button>
                        <button class="color-swatch bg-stone-900" @click="applyTextColor(block.id, 'text-stone-900'); showColors = false"></button>
                        <button class="color-swatch bg-stone-950" @click="applyTextColor(block.id, 'text-stone-950'); showColors = false"></button>
                    </div>
                    {{-- Red --}}
                    <div class="color-row">
                        <button class="color-swatch bg-red-50" @click="applyTextColor(block.id, 'text-red-50'); showColors = false"></button>
                        <button class="color-swatch bg-red-100" @click="applyTextColor(block.id, 'text-red-100'); showColors = false"></button>
                        <button class="color-swatch bg-red-200" @click="applyTextColor(block.id, 'text-red-200'); showColors = false"></button>
                        <button class="color-swatch bg-red-300" @click="applyTextColor(block.id, 'text-red-300'); showColors = false"></button>
                        <button class="color-swatch bg-red-400" @click="applyTextColor(block.id, 'text-red-400'); showColors = false"></button>
                        <button class="color-swatch bg-red-500" @click="applyTextColor(block.id, 'text-red-500'); showColors = false"></button>
                        <button class="color-swatch bg-red-600" @click="applyTextColor(block.id, 'text-red-600'); showColors = false"></button>
                        <button class="color-swatch bg-red-700" @click="applyTextColor(block.id, 'text-red-700'); showColors = false"></button>
                        <button class="color-swatch bg-red-800" @click="applyTextColor(block.id, 'text-red-800'); showColors = false"></button>
                        <button class="color-swatch bg-red-900" @click="applyTextColor(block.id, 'text-red-900'); showColors = false"></button>
                        <button class="color-swatch bg-red-950" @click="applyTextColor(block.id, 'text-red-950'); showColors = false"></button>
                    </div>
                    {{-- Orange --}}
                    <div class="color-row">
                        <button class="color-swatch bg-orange-50" @click="applyTextColor(block.id, 'text-orange-50'); showColors = false"></button>
                        <button class="color-swatch bg-orange-100" @click="applyTextColor(block.id, 'text-orange-100'); showColors = false"></button>
                        <button class="color-swatch bg-orange-200" @click="applyTextColor(block.id, 'text-orange-200'); showColors = false"></button>
                        <button class="color-swatch bg-orange-300" @click="applyTextColor(block.id, 'text-orange-300'); showColors = false"></button>
                        <button class="color-swatch bg-orange-400" @click="applyTextColor(block.id, 'text-orange-400'); showColors = false"></button>
                        <button class="color-swatch bg-orange-500" @click="applyTextColor(block.id, 'text-orange-500'); showColors = false"></button>
                        <button class="color-swatch bg-orange-600" @click="applyTextColor(block.id, 'text-orange-600'); showColors = false"></button>
                        <button class="color-swatch bg-orange-700" @click="applyTextColor(block.id, 'text-orange-700'); showColors = false"></button>
                        <button class="color-swatch bg-orange-800" @click="applyTextColor(block.id, 'text-orange-800'); showColors = false"></button>
                        <button class="color-swatch bg-orange-900" @click="applyTextColor(block.id, 'text-orange-900'); showColors = false"></button>
                        <button class="color-swatch bg-orange-950" @click="applyTextColor(block.id, 'text-orange-950'); showColors = false"></button>
                    </div>
                    {{-- Amber --}}
                    <div class="color-row">
                        <button class="color-swatch bg-amber-50" @click="applyTextColor(block.id, 'text-amber-50'); showColors = false"></button>
                        <button class="color-swatch bg-amber-100" @click="applyTextColor(block.id, 'text-amber-100'); showColors = false"></button>
                        <button class="color-swatch bg-amber-200" @click="applyTextColor(block.id, 'text-amber-200'); showColors = false"></button>
                        <button class="color-swatch bg-amber-300" @click="applyTextColor(block.id, 'text-amber-300'); showColors = false"></button>
                        <button class="color-swatch bg-amber-400" @click="applyTextColor(block.id, 'text-amber-400'); showColors = false"></button>
                        <button class="color-swatch bg-amber-500" @click="applyTextColor(block.id, 'text-amber-500'); showColors = false"></button>
                        <button class="color-swatch bg-amber-600" @click="applyTextColor(block.id, 'text-amber-600'); showColors = false"></button>
                        <button class="color-swatch bg-amber-700" @click="applyTextColor(block.id, 'text-amber-700'); showColors = false"></button>
                        <button class="color-swatch bg-amber-800" @click="applyTextColor(block.id, 'text-amber-800'); showColors = false"></button>
                        <button class="color-swatch bg-amber-900" @click="applyTextColor(block.id, 'text-amber-900'); showColors = false"></button>
                        <button class="color-swatch bg-amber-950" @click="applyTextColor(block.id, 'text-amber-950'); showColors = false"></button>
                    </div>
                    {{-- Yellow --}}
                    <div class="color-row">
                        <button class="color-swatch bg-yellow-50" @click="applyTextColor(block.id, 'text-yellow-50'); showColors = false"></button>
                        <button class="color-swatch bg-yellow-100" @click="applyTextColor(block.id, 'text-yellow-100'); showColors = false"></button>
                        <button class="color-swatch bg-yellow-200" @click="applyTextColor(block.id, 'text-yellow-200'); showColors = false"></button>
                        <button class="color-swatch bg-yellow-300" @click="applyTextColor(block.id, 'text-yellow-300'); showColors = false"></button>
                        <button class="color-swatch bg-yellow-400" @click="applyTextColor(block.id, 'text-yellow-400'); showColors = false"></button>
                        <button class="color-swatch bg-yellow-500" @click="applyTextColor(block.id, 'text-yellow-500'); showColors = false"></button>
                        <button class="color-swatch bg-yellow-600" @click="applyTextColor(block.id, 'text-yellow-600'); showColors = false"></button>
                        <button class="color-swatch bg-yellow-700" @click="applyTextColor(block.id, 'text-yellow-700'); showColors = false"></button>
                        <button class="color-swatch bg-yellow-800" @click="applyTextColor(block.id, 'text-yellow-800'); showColors = false"></button>
                        <button class="color-swatch bg-yellow-900" @click="applyTextColor(block.id, 'text-yellow-900'); showColors = false"></button>
                        <button class="color-swatch bg-yellow-950" @click="applyTextColor(block.id, 'text-yellow-950'); showColors = false"></button>
                    </div>
                    {{-- Lime --}}
                    <div class="color-row">
                        <button class="color-swatch bg-lime-50" @click="applyTextColor(block.id, 'text-lime-50'); showColors = false"></button>
                        <button class="color-swatch bg-lime-100" @click="applyTextColor(block.id, 'text-lime-100'); showColors = false"></button>
                        <button class="color-swatch bg-lime-200" @click="applyTextColor(block.id, 'text-lime-200'); showColors = false"></button>
                        <button class="color-swatch bg-lime-300" @click="applyTextColor(block.id, 'text-lime-300'); showColors = false"></button>
                        <button class="color-swatch bg-lime-400" @click="applyTextColor(block.id, 'text-lime-400'); showColors = false"></button>
                        <button class="color-swatch bg-lime-500" @click="applyTextColor(block.id, 'text-lime-500'); showColors = false"></button>
                        <button class="color-swatch bg-lime-600" @click="applyTextColor(block.id, 'text-lime-600'); showColors = false"></button>
                        <button class="color-swatch bg-lime-700" @click="applyTextColor(block.id, 'text-lime-700'); showColors = false"></button>
                        <button class="color-swatch bg-lime-800" @click="applyTextColor(block.id, 'text-lime-800'); showColors = false"></button>
                        <button class="color-swatch bg-lime-900" @click="applyTextColor(block.id, 'text-lime-900'); showColors = false"></button>
                        <button class="color-swatch bg-lime-950" @click="applyTextColor(block.id, 'text-lime-950'); showColors = false"></button>
                    </div>
                    {{-- Green --}}
                    <div class="color-row">
                        <button class="color-swatch bg-green-50" @click="applyTextColor(block.id, 'text-green-50'); showColors = false"></button>
                        <button class="color-swatch bg-green-100" @click="applyTextColor(block.id, 'text-green-100'); showColors = false"></button>
                        <button class="color-swatch bg-green-200" @click="applyTextColor(block.id, 'text-green-200'); showColors = false"></button>
                        <button class="color-swatch bg-green-300" @click="applyTextColor(block.id, 'text-green-300'); showColors = false"></button>
                        <button class="color-swatch bg-green-400" @click="applyTextColor(block.id, 'text-green-400'); showColors = false"></button>
                        <button class="color-swatch bg-green-500" @click="applyTextColor(block.id, 'text-green-500'); showColors = false"></button>
                        <button class="color-swatch bg-green-600" @click="applyTextColor(block.id, 'text-green-600'); showColors = false"></button>
                        <button class="color-swatch bg-green-700" @click="applyTextColor(block.id, 'text-green-700'); showColors = false"></button>
                        <button class="color-swatch bg-green-800" @click="applyTextColor(block.id, 'text-green-800'); showColors = false"></button>
                        <button class="color-swatch bg-green-900" @click="applyTextColor(block.id, 'text-green-900'); showColors = false"></button>
                        <button class="color-swatch bg-green-950" @click="applyTextColor(block.id, 'text-green-950'); showColors = false"></button>
                    </div>
                    {{-- Emerald --}}
                    <div class="color-row">
                        <button class="color-swatch bg-emerald-50" @click="applyTextColor(block.id, 'text-emerald-50'); showColors = false"></button>
                        <button class="color-swatch bg-emerald-100" @click="applyTextColor(block.id, 'text-emerald-100'); showColors = false"></button>
                        <button class="color-swatch bg-emerald-200" @click="applyTextColor(block.id, 'text-emerald-200'); showColors = false"></button>
                        <button class="color-swatch bg-emerald-300" @click="applyTextColor(block.id, 'text-emerald-300'); showColors = false"></button>
                        <button class="color-swatch bg-emerald-400" @click="applyTextColor(block.id, 'text-emerald-400'); showColors = false"></button>
                        <button class="color-swatch bg-emerald-500" @click="applyTextColor(block.id, 'text-emerald-500'); showColors = false"></button>
                        <button class="color-swatch bg-emerald-600" @click="applyTextColor(block.id, 'text-emerald-600'); showColors = false"></button>
                        <button class="color-swatch bg-emerald-700" @click="applyTextColor(block.id, 'text-emerald-700'); showColors = false"></button>
                        <button class="color-swatch bg-emerald-800" @click="applyTextColor(block.id, 'text-emerald-800'); showColors = false"></button>
                        <button class="color-swatch bg-emerald-900" @click="applyTextColor(block.id, 'text-emerald-900'); showColors = false"></button>
                        <button class="color-swatch bg-emerald-950" @click="applyTextColor(block.id, 'text-emerald-950'); showColors = false"></button>
                    </div>
                    {{-- Teal --}}
                    <div class="color-row">
                        <button class="color-swatch bg-teal-50" @click="applyTextColor(block.id, 'text-teal-50'); showColors = false"></button>
                        <button class="color-swatch bg-teal-100" @click="applyTextColor(block.id, 'text-teal-100'); showColors = false"></button>
                        <button class="color-swatch bg-teal-200" @click="applyTextColor(block.id, 'text-teal-200'); showColors = false"></button>
                        <button class="color-swatch bg-teal-300" @click="applyTextColor(block.id, 'text-teal-300'); showColors = false"></button>
                        <button class="color-swatch bg-teal-400" @click="applyTextColor(block.id, 'text-teal-400'); showColors = false"></button>
                        <button class="color-swatch bg-teal-500" @click="applyTextColor(block.id, 'text-teal-500'); showColors = false"></button>
                        <button class="color-swatch bg-teal-600" @click="applyTextColor(block.id, 'text-teal-600'); showColors = false"></button>
                        <button class="color-swatch bg-teal-700" @click="applyTextColor(block.id, 'text-teal-700'); showColors = false"></button>
                        <button class="color-swatch bg-teal-800" @click="applyTextColor(block.id, 'text-teal-800'); showColors = false"></button>
                        <button class="color-swatch bg-teal-900" @click="applyTextColor(block.id, 'text-teal-900'); showColors = false"></button>
                        <button class="color-swatch bg-teal-950" @click="applyTextColor(block.id, 'text-teal-950'); showColors = false"></button>
                    </div>
                    {{-- Cyan --}}
                    <div class="color-row">
                        <button class="color-swatch bg-cyan-50" @click="applyTextColor(block.id, 'text-cyan-50'); showColors = false"></button>
                        <button class="color-swatch bg-cyan-100" @click="applyTextColor(block.id, 'text-cyan-100'); showColors = false"></button>
                        <button class="color-swatch bg-cyan-200" @click="applyTextColor(block.id, 'text-cyan-200'); showColors = false"></button>
                        <button class="color-swatch bg-cyan-300" @click="applyTextColor(block.id, 'text-cyan-300'); showColors = false"></button>
                        <button class="color-swatch bg-cyan-400" @click="applyTextColor(block.id, 'text-cyan-400'); showColors = false"></button>
                        <button class="color-swatch bg-cyan-500" @click="applyTextColor(block.id, 'text-cyan-500'); showColors = false"></button>
                        <button class="color-swatch bg-cyan-600" @click="applyTextColor(block.id, 'text-cyan-600'); showColors = false"></button>
                        <button class="color-swatch bg-cyan-700" @click="applyTextColor(block.id, 'text-cyan-700'); showColors = false"></button>
                        <button class="color-swatch bg-cyan-800" @click="applyTextColor(block.id, 'text-cyan-800'); showColors = false"></button>
                        <button class="color-swatch bg-cyan-900" @click="applyTextColor(block.id, 'text-cyan-900'); showColors = false"></button>
                        <button class="color-swatch bg-cyan-950" @click="applyTextColor(block.id, 'text-cyan-950'); showColors = false"></button>
                    </div>
                    {{-- Sky --}}
                    <div class="color-row">
                        <button class="color-swatch bg-sky-50" @click="applyTextColor(block.id, 'text-sky-50'); showColors = false"></button>
                        <button class="color-swatch bg-sky-100" @click="applyTextColor(block.id, 'text-sky-100'); showColors = false"></button>
                        <button class="color-swatch bg-sky-200" @click="applyTextColor(block.id, 'text-sky-200'); showColors = false"></button>
                        <button class="color-swatch bg-sky-300" @click="applyTextColor(block.id, 'text-sky-300'); showColors = false"></button>
                        <button class="color-swatch bg-sky-400" @click="applyTextColor(block.id, 'text-sky-400'); showColors = false"></button>
                        <button class="color-swatch bg-sky-500" @click="applyTextColor(block.id, 'text-sky-500'); showColors = false"></button>
                        <button class="color-swatch bg-sky-600" @click="applyTextColor(block.id, 'text-sky-600'); showColors = false"></button>
                        <button class="color-swatch bg-sky-700" @click="applyTextColor(block.id, 'text-sky-700'); showColors = false"></button>
                        <button class="color-swatch bg-sky-800" @click="applyTextColor(block.id, 'text-sky-800'); showColors = false"></button>
                        <button class="color-swatch bg-sky-900" @click="applyTextColor(block.id, 'text-sky-900'); showColors = false"></button>
                        <button class="color-swatch bg-sky-950" @click="applyTextColor(block.id, 'text-sky-950'); showColors = false"></button>
                    </div>
                    {{-- Blue --}}
                    <div class="color-row">
                        <button class="color-swatch bg-blue-50" @click="applyTextColor(block.id, 'text-blue-50'); showColors = false"></button>
                        <button class="color-swatch bg-blue-100" @click="applyTextColor(block.id, 'text-blue-100'); showColors = false"></button>
                        <button class="color-swatch bg-blue-200" @click="applyTextColor(block.id, 'text-blue-200'); showColors = false"></button>
                        <button class="color-swatch bg-blue-300" @click="applyTextColor(block.id, 'text-blue-300'); showColors = false"></button>
                        <button class="color-swatch bg-blue-400" @click="applyTextColor(block.id, 'text-blue-400'); showColors = false"></button>
                        <button class="color-swatch bg-blue-500" @click="applyTextColor(block.id, 'text-blue-500'); showColors = false"></button>
                        <button class="color-swatch bg-blue-600" @click="applyTextColor(block.id, 'text-blue-600'); showColors = false"></button>
                        <button class="color-swatch bg-blue-700" @click="applyTextColor(block.id, 'text-blue-700'); showColors = false"></button>
                        <button class="color-swatch bg-blue-800" @click="applyTextColor(block.id, 'text-blue-800'); showColors = false"></button>
                        <button class="color-swatch bg-blue-900" @click="applyTextColor(block.id, 'text-blue-900'); showColors = false"></button>
                        <button class="color-swatch bg-blue-950" @click="applyTextColor(block.id, 'text-blue-950'); showColors = false"></button>
                    </div>
                    {{-- Indigo --}}
                    <div class="color-row">
                        <button class="color-swatch bg-indigo-50" @click="applyTextColor(block.id, 'text-indigo-50'); showColors = false"></button>
                        <button class="color-swatch bg-indigo-100" @click="applyTextColor(block.id, 'text-indigo-100'); showColors = false"></button>
                        <button class="color-swatch bg-indigo-200" @click="applyTextColor(block.id, 'text-indigo-200'); showColors = false"></button>
                        <button class="color-swatch bg-indigo-300" @click="applyTextColor(block.id, 'text-indigo-300'); showColors = false"></button>
                        <button class="color-swatch bg-indigo-400" @click="applyTextColor(block.id, 'text-indigo-400'); showColors = false"></button>
                        <button class="color-swatch bg-indigo-500" @click="applyTextColor(block.id, 'text-indigo-500'); showColors = false"></button>
                        <button class="color-swatch bg-indigo-600" @click="applyTextColor(block.id, 'text-indigo-600'); showColors = false"></button>
                        <button class="color-swatch bg-indigo-700" @click="applyTextColor(block.id, 'text-indigo-700'); showColors = false"></button>
                        <button class="color-swatch bg-indigo-800" @click="applyTextColor(block.id, 'text-indigo-800'); showColors = false"></button>
                        <button class="color-swatch bg-indigo-900" @click="applyTextColor(block.id, 'text-indigo-900'); showColors = false"></button>
                        <button class="color-swatch bg-indigo-950" @click="applyTextColor(block.id, 'text-indigo-950'); showColors = false"></button>
                    </div>
                    {{-- Violet --}}
                    <div class="color-row">
                        <button class="color-swatch bg-violet-50" @click="applyTextColor(block.id, 'text-violet-50'); showColors = false"></button>
                        <button class="color-swatch bg-violet-100" @click="applyTextColor(block.id, 'text-violet-100'); showColors = false"></button>
                        <button class="color-swatch bg-violet-200" @click="applyTextColor(block.id, 'text-violet-200'); showColors = false"></button>
                        <button class="color-swatch bg-violet-300" @click="applyTextColor(block.id, 'text-violet-300'); showColors = false"></button>
                        <button class="color-swatch bg-violet-400" @click="applyTextColor(block.id, 'text-violet-400'); showColors = false"></button>
                        <button class="color-swatch bg-violet-500" @click="applyTextColor(block.id, 'text-violet-500'); showColors = false"></button>
                        <button class="color-swatch bg-violet-600" @click="applyTextColor(block.id, 'text-violet-600'); showColors = false"></button>
                        <button class="color-swatch bg-violet-700" @click="applyTextColor(block.id, 'text-violet-700'); showColors = false"></button>
                        <button class="color-swatch bg-violet-800" @click="applyTextColor(block.id, 'text-violet-800'); showColors = false"></button>
                        <button class="color-swatch bg-violet-900" @click="applyTextColor(block.id, 'text-violet-900'); showColors = false"></button>
                        <button class="color-swatch bg-violet-950" @click="applyTextColor(block.id, 'text-violet-950'); showColors = false"></button>
                    </div>
                    {{-- Purple --}}
                    <div class="color-row">
                        <button class="color-swatch bg-purple-50" @click="applyTextColor(block.id, 'text-purple-50'); showColors = false"></button>
                        <button class="color-swatch bg-purple-100" @click="applyTextColor(block.id, 'text-purple-100'); showColors = false"></button>
                        <button class="color-swatch bg-purple-200" @click="applyTextColor(block.id, 'text-purple-200'); showColors = false"></button>
                        <button class="color-swatch bg-purple-300" @click="applyTextColor(block.id, 'text-purple-300'); showColors = false"></button>
                        <button class="color-swatch bg-purple-400" @click="applyTextColor(block.id, 'text-purple-400'); showColors = false"></button>
                        <button class="color-swatch bg-purple-500" @click="applyTextColor(block.id, 'text-purple-500'); showColors = false"></button>
                        <button class="color-swatch bg-purple-600" @click="applyTextColor(block.id, 'text-purple-600'); showColors = false"></button>
                        <button class="color-swatch bg-purple-700" @click="applyTextColor(block.id, 'text-purple-700'); showColors = false"></button>
                        <button class="color-swatch bg-purple-800" @click="applyTextColor(block.id, 'text-purple-800'); showColors = false"></button>
                        <button class="color-swatch bg-purple-900" @click="applyTextColor(block.id, 'text-purple-900'); showColors = false"></button>
                        <button class="color-swatch bg-purple-950" @click="applyTextColor(block.id, 'text-purple-950'); showColors = false"></button>
                    </div>
                    {{-- Fuchsia --}}
                    <div class="color-row">
                        <button class="color-swatch bg-fuchsia-50" @click="applyTextColor(block.id, 'text-fuchsia-50'); showColors = false"></button>
                        <button class="color-swatch bg-fuchsia-100" @click="applyTextColor(block.id, 'text-fuchsia-100'); showColors = false"></button>
                        <button class="color-swatch bg-fuchsia-200" @click="applyTextColor(block.id, 'text-fuchsia-200'); showColors = false"></button>
                        <button class="color-swatch bg-fuchsia-300" @click="applyTextColor(block.id, 'text-fuchsia-300'); showColors = false"></button>
                        <button class="color-swatch bg-fuchsia-400" @click="applyTextColor(block.id, 'text-fuchsia-400'); showColors = false"></button>
                        <button class="color-swatch bg-fuchsia-500" @click="applyTextColor(block.id, 'text-fuchsia-500'); showColors = false"></button>
                        <button class="color-swatch bg-fuchsia-600" @click="applyTextColor(block.id, 'text-fuchsia-600'); showColors = false"></button>
                        <button class="color-swatch bg-fuchsia-700" @click="applyTextColor(block.id, 'text-fuchsia-700'); showColors = false"></button>
                        <button class="color-swatch bg-fuchsia-800" @click="applyTextColor(block.id, 'text-fuchsia-800'); showColors = false"></button>
                        <button class="color-swatch bg-fuchsia-900" @click="applyTextColor(block.id, 'text-fuchsia-900'); showColors = false"></button>
                        <button class="color-swatch bg-fuchsia-950" @click="applyTextColor(block.id, 'text-fuchsia-950'); showColors = false"></button>
                    </div>
                    {{-- Pink --}}
                    <div class="color-row">
                        <button class="color-swatch bg-pink-50" @click="applyTextColor(block.id, 'text-pink-50'); showColors = false"></button>
                        <button class="color-swatch bg-pink-100" @click="applyTextColor(block.id, 'text-pink-100'); showColors = false"></button>
                        <button class="color-swatch bg-pink-200" @click="applyTextColor(block.id, 'text-pink-200'); showColors = false"></button>
                        <button class="color-swatch bg-pink-300" @click="applyTextColor(block.id, 'text-pink-300'); showColors = false"></button>
                        <button class="color-swatch bg-pink-400" @click="applyTextColor(block.id, 'text-pink-400'); showColors = false"></button>
                        <button class="color-swatch bg-pink-500" @click="applyTextColor(block.id, 'text-pink-500'); showColors = false"></button>
                        <button class="color-swatch bg-pink-600" @click="applyTextColor(block.id, 'text-pink-600'); showColors = false"></button>
                        <button class="color-swatch bg-pink-700" @click="applyTextColor(block.id, 'text-pink-700'); showColors = false"></button>
                        <button class="color-swatch bg-pink-800" @click="applyTextColor(block.id, 'text-pink-800'); showColors = false"></button>
                        <button class="color-swatch bg-pink-900" @click="applyTextColor(block.id, 'text-pink-900'); showColors = false"></button>
                        <button class="color-swatch bg-pink-950" @click="applyTextColor(block.id, 'text-pink-950'); showColors = false"></button>
                    </div>
                    {{-- Rose --}}
                    <div class="color-row">
                        <button class="color-swatch bg-rose-50" @click="applyTextColor(block.id, 'text-rose-50'); showColors = false"></button>
                        <button class="color-swatch bg-rose-100" @click="applyTextColor(block.id, 'text-rose-100'); showColors = false"></button>
                        <button class="color-swatch bg-rose-200" @click="applyTextColor(block.id, 'text-rose-200'); showColors = false"></button>
                        <button class="color-swatch bg-rose-300" @click="applyTextColor(block.id, 'text-rose-300'); showColors = false"></button>
                        <button class="color-swatch bg-rose-400" @click="applyTextColor(block.id, 'text-rose-400'); showColors = false"></button>
                        <button class="color-swatch bg-rose-500" @click="applyTextColor(block.id, 'text-rose-500'); showColors = false"></button>
                        <button class="color-swatch bg-rose-600" @click="applyTextColor(block.id, 'text-rose-600'); showColors = false"></button>
                        <button class="color-swatch bg-rose-700" @click="applyTextColor(block.id, 'text-rose-700'); showColors = false"></button>
                        <button class="color-swatch bg-rose-800" @click="applyTextColor(block.id, 'text-rose-800'); showColors = false"></button>
                        <button class="color-swatch bg-rose-900" @click="applyTextColor(block.id, 'text-rose-900'); showColors = false"></button>
                        <button class="color-swatch bg-rose-950" @click="applyTextColor(block.id, 'text-rose-950'); showColors = false"></button>
                    </div>
                    {{-- Reset (Remover cor) --}}
                    <div class="color-row">
                        <button class="color-swatch bg-black" title="Remover cor" @click="applyTextColor(block.id, ''); showColors = false"></button>
                    </div>
                </div>
                <div 
                    x-show="showColors"
                    @click.outside="showColors = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="block-toolbar-color-grid"
                >
                    {{-- Slate --}}
                    <div class="color-row">
                        <button class="color-swatch bg-slate-400" @click="applyTextColor(block.id, 'text-slate-400'); showColors = false"></button>
                        <button class="color-swatch bg-slate-500" @click="applyTextColor(block.id, 'text-slate-500'); showColors = false"></button>
                        <button class="color-swatch bg-slate-600" @click="applyTextColor(block.id, 'text-slate-600'); showColors = false"></button>
                        <button class="color-swatch bg-slate-700" @click="applyTextColor(block.id, 'text-slate-700'); showColors = false"></button>
                        <button class="color-swatch bg-slate-800" @click="applyTextColor(block.id, 'text-slate-800'); showColors = false"></button>
                        <button class="color-swatch bg-slate-900" @click="applyTextColor(block.id, 'text-slate-900'); showColors = false"></button>
                    </div>
                    {{-- Gray --}}
                    <div class="color-row">
                        <button class="color-swatch bg-gray-400" @click="applyTextColor(block.id, 'text-gray-400'); showColors = false"></button>
                        <button class="color-swatch bg-gray-500" @click="applyTextColor(block.id, 'text-gray-500'); showColors = false"></button>
                        <button class="color-swatch bg-gray-600" @click="applyTextColor(block.id, 'text-gray-600'); showColors = false"></button>
                        <button class="color-swatch bg-gray-700" @click="applyTextColor(block.id, 'text-gray-700'); showColors = false"></button>
                        <button class="color-swatch bg-gray-800" @click="applyTextColor(block.id, 'text-gray-800'); showColors = false"></button>
                        <button class="color-swatch bg-gray-900" @click="applyTextColor(block.id, 'text-gray-900'); showColors = false"></button>
                    </div>
                    {{-- Zinc --}}
                    <div class="color-row">
                        <button class="color-swatch bg-zinc-400" @click="applyTextColor(block.id, 'text-zinc-400'); showColors = false"></button>
                        <button class="color-swatch bg-zinc-500" @click="applyTextColor(block.id, 'text-zinc-500'); showColors = false"></button>
                        <button class="color-swatch bg-zinc-600" @click="applyTextColor(block.id, 'text-zinc-600'); showColors = false"></button>
                        <button class="color-swatch bg-zinc-700" @click="applyTextColor(block.id, 'text-zinc-700'); showColors = false"></button>
                        <button class="color-swatch bg-zinc-800" @click="applyTextColor(block.id, 'text-zinc-800'); showColors = false"></button>
                        <button class="color-swatch bg-zinc-900" @click="applyTextColor(block.id, 'text-zinc-900'); showColors = false"></button>
                    </div>
                    {{-- Red --}}
                    <div class="color-row">
                        <button class="color-swatch bg-red-400" @click="applyTextColor(block.id, 'text-red-400'); showColors = false"></button>
                        <button class="color-swatch bg-red-500" @click="applyTextColor(block.id, 'text-red-500'); showColors = false"></button>
                        <button class="color-swatch bg-red-600" @click="applyTextColor(block.id, 'text-red-600'); showColors = false"></button>
                        <button class="color-swatch bg-red-700" @click="applyTextColor(block.id, 'text-red-700'); showColors = false"></button>
                        <button class="color-swatch bg-red-800" @click="applyTextColor(block.id, 'text-red-800'); showColors = false"></button>
                        <button class="color-swatch bg-red-900" @click="applyTextColor(block.id, 'text-red-900'); showColors = false"></button>
                    </div>
                    {{-- Orange --}}
                    <div class="color-row">
                        <button class="color-swatch bg-orange-400" @click="applyTextColor(block.id, 'text-orange-400'); showColors = false"></button>
                        <button class="color-swatch bg-orange-500" @click="applyTextColor(block.id, 'text-orange-500'); showColors = false"></button>
                        <button class="color-swatch bg-orange-600" @click="applyTextColor(block.id, 'text-orange-600'); showColors = false"></button>
                        <button class="color-swatch bg-orange-700" @click="applyTextColor(block.id, 'text-orange-700'); showColors = false"></button>
                        <button class="color-swatch bg-orange-800" @click="applyTextColor(block.id, 'text-orange-800'); showColors = false"></button>
                        <button class="color-swatch bg-orange-900" @click="applyTextColor(block.id, 'text-orange-900'); showColors = false"></button>
                    </div>
                    {{-- Amber --}}
                    <div class="color-row">
                        <button class="color-swatch bg-amber-400" @click="applyTextColor(block.id, 'text-amber-400'); showColors = false"></button>
                        <button class="color-swatch bg-amber-500" @click="applyTextColor(block.id, 'text-amber-500'); showColors = false"></button>
                        <button class="color-swatch bg-amber-600" @click="applyTextColor(block.id, 'text-amber-600'); showColors = false"></button>
                        <button class="color-swatch bg-amber-700" @click="applyTextColor(block.id, 'text-amber-700'); showColors = false"></button>
                        <button class="color-swatch bg-amber-800" @click="applyTextColor(block.id, 'text-amber-800'); showColors = false"></button>
                        <button class="color-swatch bg-amber-900" @click="applyTextColor(block.id, 'text-amber-900'); showColors = false"></button>
                    </div>
                    {{-- Yellow --}}
                    <div class="color-row">
                        <button class="color-swatch bg-yellow-400" @click="applyTextColor(block.id, 'text-yellow-400'); showColors = false"></button>
                        <button class="color-swatch bg-yellow-500" @click="applyTextColor(block.id, 'text-yellow-500'); showColors = false"></button>
                        <button class="color-swatch bg-yellow-600" @click="applyTextColor(block.id, 'text-yellow-600'); showColors = false"></button>
                        <button class="color-swatch bg-yellow-700" @click="applyTextColor(block.id, 'text-yellow-700'); showColors = false"></button>
                        <button class="color-swatch bg-yellow-800" @click="applyTextColor(block.id, 'text-yellow-800'); showColors = false"></button>
                        <button class="color-swatch bg-yellow-900" @click="applyTextColor(block.id, 'text-yellow-900'); showColors = false"></button>
                    </div>
                    {{-- Lime --}}
                    <div class="color-row">
                        <button class="color-swatch bg-lime-400" @click="applyTextColor(block.id, 'text-lime-400'); showColors = false"></button>
                        <button class="color-swatch bg-lime-500" @click="applyTextColor(block.id, 'text-lime-500'); showColors = false"></button>
                        <button class="color-swatch bg-lime-600" @click="applyTextColor(block.id, 'text-lime-600'); showColors = false"></button>
                        <button class="color-swatch bg-lime-700" @click="applyTextColor(block.id, 'text-lime-700'); showColors = false"></button>
                        <button class="color-swatch bg-lime-800" @click="applyTextColor(block.id, 'text-lime-800'); showColors = false"></button>
                        <button class="color-swatch bg-lime-900" @click="applyTextColor(block.id, 'text-lime-900'); showColors = false"></button>
                    </div>
                    {{-- Green --}}
                    <div class="color-row">
                        <button class="color-swatch bg-green-400" @click="applyTextColor(block.id, 'text-green-400'); showColors = false"></button>
                        <button class="color-swatch bg-green-500" @click="applyTextColor(block.id, 'text-green-500'); showColors = false"></button>
                        <button class="color-swatch bg-green-600" @click="applyTextColor(block.id, 'text-green-600'); showColors = false"></button>
                        <button class="color-swatch bg-green-700" @click="applyTextColor(block.id, 'text-green-700'); showColors = false"></button>
                        <button class="color-swatch bg-green-800" @click="applyTextColor(block.id, 'text-green-800'); showColors = false"></button>
                        <button class="color-swatch bg-green-900" @click="applyTextColor(block.id, 'text-green-900'); showColors = false"></button>
                    </div>
                    {{-- Emerald --}}
                    <div class="color-row">
                        <button class="color-swatch bg-emerald-400" @click="applyTextColor(block.id, 'text-emerald-400'); showColors = false"></button>
                        <button class="color-swatch bg-emerald-500" @click="applyTextColor(block.id, 'text-emerald-500'); showColors = false"></button>
                        <button class="color-swatch bg-emerald-600" @click="applyTextColor(block.id, 'text-emerald-600'); showColors = false"></button>
                        <button class="color-swatch bg-emerald-700" @click="applyTextColor(block.id, 'text-emerald-700'); showColors = false"></button>
                        <button class="color-swatch bg-emerald-800" @click="applyTextColor(block.id, 'text-emerald-800'); showColors = false"></button>
                        <button class="color-swatch bg-emerald-900" @click="applyTextColor(block.id, 'text-emerald-900'); showColors = false"></button>
                    </div>
                    {{-- Teal --}}
                    <div class="color-row">
                        <button class="color-swatch bg-teal-400" @click="applyTextColor(block.id, 'text-teal-400'); showColors = false"></button>
                        <button class="color-swatch bg-teal-500" @click="applyTextColor(block.id, 'text-teal-500'); showColors = false"></button>
                        <button class="color-swatch bg-teal-600" @click="applyTextColor(block.id, 'text-teal-600'); showColors = false"></button>
                        <button class="color-swatch bg-teal-700" @click="applyTextColor(block.id, 'text-teal-700'); showColors = false"></button>
                        <button class="color-swatch bg-teal-800" @click="applyTextColor(block.id, 'text-teal-800'); showColors = false"></button>
                        <button class="color-swatch bg-teal-900" @click="applyTextColor(block.id, 'text-teal-900'); showColors = false"></button>
                    </div>
                    {{-- Cyan --}}
                    <div class="color-row">
                        <button class="color-swatch bg-cyan-400" @click="applyTextColor(block.id, 'text-cyan-400'); showColors = false"></button>
                        <button class="color-swatch bg-cyan-500" @click="applyTextColor(block.id, 'text-cyan-500'); showColors = false"></button>
                        <button class="color-swatch bg-cyan-600" @click="applyTextColor(block.id, 'text-cyan-600'); showColors = false"></button>
                        <button class="color-swatch bg-cyan-700" @click="applyTextColor(block.id, 'text-cyan-700'); showColors = false"></button>
                        <button class="color-swatch bg-cyan-800" @click="applyTextColor(block.id, 'text-cyan-800'); showColors = false"></button>
                        <button class="color-swatch bg-cyan-900" @click="applyTextColor(block.id, 'text-cyan-900'); showColors = false"></button>
                    </div>
                    {{-- Sky --}}
                    <div class="color-row">
                        <button class="color-swatch bg-sky-400" @click="applyTextColor(block.id, 'text-sky-400'); showColors = false"></button>
                        <button class="color-swatch bg-sky-500" @click="applyTextColor(block.id, 'text-sky-500'); showColors = false"></button>
                        <button class="color-swatch bg-sky-600" @click="applyTextColor(block.id, 'text-sky-600'); showColors = false"></button>
                        <button class="color-swatch bg-sky-700" @click="applyTextColor(block.id, 'text-sky-700'); showColors = false"></button>
                        <button class="color-swatch bg-sky-800" @click="applyTextColor(block.id, 'text-sky-800'); showColors = false"></button>
                        <button class="color-swatch bg-sky-900" @click="applyTextColor(block.id, 'text-sky-900'); showColors = false"></button>
                    </div>
                    {{-- Blue --}}
                    <div class="color-row">
                        <button class="color-swatch bg-blue-400" @click="applyTextColor(block.id, 'text-blue-400'); showColors = false"></button>
                        <button class="color-swatch bg-blue-500" @click="applyTextColor(block.id, 'text-blue-500'); showColors = false"></button>
                        <button class="color-swatch bg-blue-600" @click="applyTextColor(block.id, 'text-blue-600'); showColors = false"></button>
                        <button class="color-swatch bg-blue-700" @click="applyTextColor(block.id, 'text-blue-700'); showColors = false"></button>
                        <button class="color-swatch bg-blue-800" @click="applyTextColor(block.id, 'text-blue-800'); showColors = false"></button>
                        <button class="color-swatch bg-blue-900" @click="applyTextColor(block.id, 'text-blue-900'); showColors = false"></button>
                    </div>
                    {{-- Indigo --}}
                    <div class="color-row">
                        <button class="color-swatch bg-indigo-400" @click="applyTextColor(block.id, 'text-indigo-400'); showColors = false"></button>
                        <button class="color-swatch bg-indigo-500" @click="applyTextColor(block.id, 'text-indigo-500'); showColors = false"></button>
                        <button class="color-swatch bg-indigo-600" @click="applyTextColor(block.id, 'text-indigo-600'); showColors = false"></button>
                        <button class="color-swatch bg-indigo-700" @click="applyTextColor(block.id, 'text-indigo-700'); showColors = false"></button>
                        <button class="color-swatch bg-indigo-800" @click="applyTextColor(block.id, 'text-indigo-800'); showColors = false"></button>
                        <button class="color-swatch bg-indigo-900" @click="applyTextColor(block.id, 'text-indigo-900'); showColors = false"></button>
                    </div>
                    {{-- Violet --}}
                    <div class="color-row">
                        <button class="color-swatch bg-violet-400" @click="applyTextColor(block.id, 'text-violet-400'); showColors = false"></button>
                        <button class="color-swatch bg-violet-500" @click="applyTextColor(block.id, 'text-violet-500'); showColors = false"></button>
                        <button class="color-swatch bg-violet-600" @click="applyTextColor(block.id, 'text-violet-600'); showColors = false"></button>
                        <button class="color-swatch bg-violet-700" @click="applyTextColor(block.id, 'text-violet-700'); showColors = false"></button>
                        <button class="color-swatch bg-violet-800" @click="applyTextColor(block.id, 'text-violet-800'); showColors = false"></button>
                        <button class="color-swatch bg-violet-900" @click="applyTextColor(block.id, 'text-violet-900'); showColors = false"></button>
                    </div>
                    {{-- Purple --}}
                    <div class="color-row">
                        <button class="color-swatch bg-purple-400" @click="applyTextColor(block.id, 'text-purple-400'); showColors = false"></button>
                        <button class="color-swatch bg-purple-500" @click="applyTextColor(block.id, 'text-purple-500'); showColors = false"></button>
                        <button class="color-swatch bg-purple-600" @click="applyTextColor(block.id, 'text-purple-600'); showColors = false"></button>
                        <button class="color-swatch bg-purple-700" @click="applyTextColor(block.id, 'text-purple-700'); showColors = false"></button>
                        <button class="color-swatch bg-purple-800" @click="applyTextColor(block.id, 'text-purple-800'); showColors = false"></button>
                        <button class="color-swatch bg-purple-900" @click="applyTextColor(block.id, 'text-purple-900'); showColors = false"></button>
                    </div>
                    {{-- Fuchsia --}}
                    <div class="color-row">
                        <button class="color-swatch bg-fuchsia-400" @click="applyTextColor(block.id, 'text-fuchsia-400'); showColors = false"></button>
                        <button class="color-swatch bg-fuchsia-500" @click="applyTextColor(block.id, 'text-fuchsia-500'); showColors = false"></button>
                        <button class="color-swatch bg-fuchsia-600" @click="applyTextColor(block.id, 'text-fuchsia-600'); showColors = false"></button>
                        <button class="color-swatch bg-fuchsia-700" @click="applyTextColor(block.id, 'text-fuchsia-700'); showColors = false"></button>
                        <button class="color-swatch bg-fuchsia-800" @click="applyTextColor(block.id, 'text-fuchsia-800'); showColors = false"></button>
                        <button class="color-swatch bg-fuchsia-900" @click="applyTextColor(block.id, 'text-fuchsia-900'); showColors = false"></button>
                    </div>
                    {{-- Pink --}}
                    <div class="color-row">
                        <button class="color-swatch bg-pink-400" @click="applyTextColor(block.id, 'text-pink-400'); showColors = false"></button>
                        <button class="color-swatch bg-pink-500" @click="applyTextColor(block.id, 'text-pink-500'); showColors = false"></button>
                        <button class="color-swatch bg-pink-600" @click="applyTextColor(block.id, 'text-pink-600'); showColors = false"></button>
                        <button class="color-swatch bg-pink-700" @click="applyTextColor(block.id, 'text-pink-700'); showColors = false"></button>
                        <button class="color-swatch bg-pink-800" @click="applyTextColor(block.id, 'text-pink-800'); showColors = false"></button>
                        <button class="color-swatch bg-pink-900" @click="applyTextColor(block.id, 'text-pink-900'); showColors = false"></button>
                    </div>
                    {{-- Rose --}}
                    <div class="color-row">
                        <button class="color-swatch bg-rose-400" @click="applyTextColor(block.id, 'text-rose-400'); showColors = false"></button>
                        <button class="color-swatch bg-rose-500" @click="applyTextColor(block.id, 'text-rose-500'); showColors = false"></button>
                        <button class="color-swatch bg-rose-600" @click="applyTextColor(block.id, 'text-rose-600'); showColors = false"></button>
                        <button class="color-swatch bg-rose-700" @click="applyTextColor(block.id, 'text-rose-700'); showColors = false"></button>
                        <button class="color-swatch bg-rose-800" @click="applyTextColor(block.id, 'text-rose-800'); showColors = false"></button>
                        <button class="color-swatch bg-rose-900" @click="applyTextColor(block.id, 'text-rose-900'); showColors = false"></button>
                    </div>
                    {{-- Reset (Remover cor) --}}
                    <div class="color-row">
                        <button class="color-swatch bg-black" title="Remover cor" @click="applyTextColor(block.id, ''); showColors = false"></button>
                    </div>
                </div>
            </div>
        </div>
    </template>
    
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
