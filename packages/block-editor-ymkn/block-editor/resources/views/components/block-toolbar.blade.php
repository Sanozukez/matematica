{{-- Block Toolbar Universal (WordPress Style) --}}
{{-- Aparece flutuante acima de qualquer bloco focado --}}
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
    {{-- Seção 1: Transformação de Tipo --}}
    <div class="block-toolbar-section">
        {{-- Drag Handle (6 pontos) --}}
        <button class="block-toolbar-btn" title="Arrastar" disabled>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5zM6.75 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM17.25 12.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
            </svg>
        </button>

        {{-- Block Type Switcher --}}
        <div class="block-toolbar-dropdown">
            <button class="block-toolbar-btn block-toolbar-type-btn">
                <span x-text="blockTypes.find(t => t.type === block.type)?.label || 'Bloco'"></span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width: 14px; height: 14px;">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>

    <div class="block-toolbar-divider"></div>

    {{-- Seção 2: Movimentação --}}
    <div class="block-toolbar-section">
        <button class="block-toolbar-btn" title="Mover para cima" disabled>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
            </svg>
        </button>
        <button class="block-toolbar-btn" title="Mover para baixo" disabled>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        </button>
    </div>

    <div class="block-toolbar-divider"></div>

    {{-- Seção 3: Formatação de Texto (apenas para blocos de texto) --}}
    <template x-if="['paragraph', 'heading', 'quote'].includes(block.type)">
        <div class="block-toolbar-section">
            <button class="block-toolbar-btn" title="Negrito (Ctrl+B)" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 4.5h4.5c1.5 0 2.75 1.25 2.75 2.75s-1.25 2.75-2.75 2.75h-4.5M6.75 10h5.25c1.5 0 2.75 1.25 2.75 2.75s-1.25 2.75-2.75 2.75h-5.25M6.75 4.5v11" />
                </svg>
            </button>
            <button class="block-toolbar-btn" title="Itálico (Ctrl+I)" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5h7.5M4.5 19.5h7.5m-6-15l-3 15" />
                </svg>
            </button>
            <button class="block-toolbar-btn" title="Link (Ctrl+K)" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                </svg>
            </button>
        </div>
    </template>

    {{-- Seção 4: Opções --}}
    <div class="block-toolbar-divider"></div>
    <div class="block-toolbar-section">
        <button class="block-toolbar-btn" title="Mais opções">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
            </svg>
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
