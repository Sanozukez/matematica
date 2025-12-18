{{-- Block Inserter Sidebar (Estilo WordPress) --}}
<div 
    x-show="showBlockInserter" 
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-x-full"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 -translate-x-full"
    class="block-inserter-wp"
    @keydown.escape.window="showBlockInserter = false; canvasShifted = false"
>
    {{-- Header --}}
    <div class="block-inserter-header-wp">
        <h3 class="block-inserter-title-wp">Adicionar Bloco</h3>
        <button 
            class="block-inserter-close-wp" 
            @click="showBlockInserter = false; canvasShifted = false"
            title="Fechar (Esc)"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    {{-- Search Bar --}}
    <div class="block-inserter-search-wp">
        <div class="search-input-wrapper-wp">
            <svg class="search-icon-wp" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
            <input 
                type="text" 
                placeholder="Buscar blocos..."
                x-model="blockSearchQuery"
                class="block-inserter-search-input-wp"
                @input="filterBlocks()"
            >
        </div>
    </div>
    
    {{-- Block List --}}
    <div class="block-inserter-list-wp">
        <template x-for="blockType in filteredBlockTypes" :key="blockType.type">
            <button 
                class="block-inserter-item-wp"
                @click="insertBlockFromModal(blockType.type)"
            >
                <div class="block-inserter-icon-wp" x-html="blockType.icon"></div>
                <div class="block-inserter-text-wp">
                    <span class="block-inserter-label-wp" x-text="blockType.label"></span>
                    <span class="block-inserter-description-wp" x-text="blockType.description"></span>
                </div>
            </button>
        </template>
        
        {{-- Empty State --}}
        <div x-show="filteredBlockTypes.length === 0" class="block-inserter-empty-wp">
            Nenhum bloco encontrado
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>
