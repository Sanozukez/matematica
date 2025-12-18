{{-- Toolbar: Link Button --}}
<template x-if="focusedBlockId === block.id">
    <div class="block-toolbar-section">
        <button 
            class="block-toolbar-btn" 
            title="Inserir link"
            @click="showLinkInput = !showLinkInput"
        >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
            </svg>
        </button>
        
        {{-- Link Input Popup --}}
        <div 
            x-show="showLinkInput"
            @click.outside="showLinkInput = false"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="block-toolbar-popup"
        >
            <input 
                type="url" 
                x-model="linkUrl"
                @keydown.enter="applyLink(block.id, linkUrl); showLinkInput = false; linkUrl = ''"
                placeholder="https://exemplo.com"
                class="block-toolbar-input"
            />
            <div class="block-toolbar-popup-buttons">
                <button 
                    @click="applyLink(block.id, linkUrl); showLinkInput = false; linkUrl = ''"
                    class="block-toolbar-popup-btn primary"
                >
                    Aplicar
                </button>
                <button 
                    @click="showLinkInput = false; linkUrl = ''"
                    class="block-toolbar-popup-btn"
                >
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</template>
