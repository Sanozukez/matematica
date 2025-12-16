{{-- Block Inserter Modal (Popup de Seleção de Blocos) --}}
<div 
    x-show="showBlockInserter" 
    x-cloak
    class="block-inserter-overlay"
    @click.self="showBlockInserter = false"
    @keydown.escape.window="showBlockInserter = false"
>
    <div class="block-inserter-modal">
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
            <input 
                type="text" 
                placeholder="Buscar blocos..."
                x-model="blockSearchQuery"
                class="block-inserter-search-input"
                @input="filterBlocks()"
            >
        </div>
        
        {{-- Block Grid --}}
        <div class="block-inserter-grid">
            <template x-for="blockType in filteredBlockTypes" :key="blockType.type">
                <button 
                    class="block-inserter-item"
                    @click="insertBlockFromModal(blockType.type)"
                >
                    <div class="block-inserter-item-icon" x-html="blockType.icon"></div>
                    <div class="block-inserter-item-label" x-text="blockType.label"></div>
                    <div class="block-inserter-item-description" x-text="blockType.description"></div>
                </button>
            </template>
        </div>
    </div>
    
    <style>
        [x-cloak] { display: none !important; }
        
        .block-inserter-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeIn 0.2s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .block-inserter-modal {
            background: #FFFFFF;
            border-radius: 8px;
            width: 90%;
            max-width: 640px;
            max-height: 80vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: slideUp 0.3s ease;
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(20px); 
            }
            to { 
                opacity: 1;
                transform: translateY(0); 
            }
        }
        
        .block-inserter-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem;
            border-bottom: 1px solid #DCDCDE;
        }
        
        .block-inserter-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1E1E1E;
            margin: 0;
        }
        
        .block-inserter-close {
            width: 32px;
            height: 32px;
            border: none;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: background-color 0.2s;
        }
        
        .block-inserter-close:hover {
            background: #F0F0F0;
        }
        
        .block-inserter-close svg {
            width: 20px;
            height: 20px;
            color: #1E1E1E;
        }
        
        .block-inserter-search {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #DCDCDE;
        }
        
        .block-inserter-search-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #DCDCDE;
            border-radius: 4px;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s;
        }
        
        .block-inserter-search-input:focus {
            border-color: #007CBA;
        }
        
        .block-inserter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 0.5rem;
            padding: 1.5rem;
            overflow-y: auto;
            max-height: 400px;
        }
        
        .block-inserter-item {
            background: #FFFFFF;
            border: 1px solid #DCDCDE;
            border-radius: 4px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        
        .block-inserter-item:hover {
            background: #F0F0F0;
            border-color: #007CBA;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .block-inserter-item-icon {
            font-size: 2rem;
        }
        
        .block-inserter-item-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #1E1E1E;
        }
        
        .block-inserter-item-description {
            font-size: 0.75rem;
            color: #757575;
            line-height: 1.4;
        }
    </style>
</div>
