{{-- Block Inserter Sidebar (Estilo WordPress) --}}
<div 
    x-show="showBlockInserter" 
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="block-inserter-sidebar"
    @keydown.escape.window="showBlockInserter = false"
>
    <div class="block-inserter-inner">
        {{-- Header --}}
        <div class="block-inserter-header">
            <h3 class="block-inserter-title">Adicionar Bloco</h3>
            <button 
                class="block-inserter-close" 
                @click="showBlockInserter = false"
                title="Fechar (Esc)"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        {{-- Search Bar --}}
        <div class="block-inserter-search">
            <svg class="block-inserter-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
            <input 
                type="text" 
                placeholder="Buscar..."
                x-model="blockSearchQuery"
                class="block-inserter-search-input"
                @input="filterBlocks()"
            >
        </div>
        
        {{-- Block List (estilo WordPress) --}}
        <div class="block-inserter-list">
            <template x-for="blockType in filteredBlockTypes" :key="blockType.type">
                <button 
                    class="block-inserter-item-wp"
                    @click="insertBlockFromModal(blockType.type)"
                >
                    <div class="block-inserter-item-icon-wp" x-html="blockType.icon"></div>
                    <span class="block-inserter-item-label-wp" x-text="blockType.label"></span>
                </button>
            </template>
        </div>
    </div>
    
    <style>
        [x-cloak] { display: none !important; }
        
        .block-inserter-sidebar {
            position: fixed;
            top: 65px; /* Abaixo da navbar */
            left: 0;
            bottom: 0;
            width: 280px;
            background: #FFFFFF;
            border-right: 1px solid #DCDCDE;
            z-index: 999;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }
        
        .block-inserter-inner {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .block-inserter-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #DCDCDE;
            flex-shrink: 0;
        }
        
        .block-inserter-title {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #1E1E1E;
            margin: 0;
        }
        
        .block-inserter-close {
            width: 28px;
            height: 28px;
            border: none;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: background-color 0.15s;
        }
        
        .block-inserter-close:hover {
            background: #F0F0F0;
        }
        
        .block-inserter-close svg {
            width: 18px;
            height: 18px;
            color: #6B7280;
        }
        
        .block-inserter-search {
            position: relative;
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid #DCDCDE;
            flex-shrink: 0;
        }
        
        .block-inserter-search-icon {
            position: absolute;
            left: 1.75rem;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: #9CA3AF;
            pointer-events: none;
        }
        
        .block-inserter-search-input {
            width: 100%;
            padding: 0.5rem 0.75rem 0.5rem 2rem;
            border: 1px solid #DCDCDE;
            border-radius: 4px;
            font-size: 0.8125rem;
            outline: none;
            transition: border-color 0.15s;
        }
        
        .block-inserter-search-input:focus {
            border-color: #007CBA;
        }
        
        .block-inserter-list {
            flex: 1;
            overflow-y: auto;
            padding: 0.5rem;
        }
        
        /* Item estilo WordPress: ícone à esquerda, texto à direita */
        .block-inserter-item-wp {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.75rem;
            background: transparent;
            border: 1px solid transparent;
            border-radius: 4px;
            text-align: left;
            cursor: pointer;
            transition: all 0.15s;
            margin-bottom: 0.25rem;
        }
        
        .block-inserter-item-wp:hover {
            background: #F0F0F0;
            border-color: #DCDCDE;
        }
        
        .block-inserter-item-icon-wp {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #1E1E1E;
        }
        
        .block-inserter-item-icon-wp svg {
            width: 20px;
            height: 20px;
        }
        
        .block-inserter-item-label-wp {
            font-size: 0.8125rem;
            font-weight: 400;
            color: #1E1E1E;
            flex: 1;
        }
    </style>
</div>
