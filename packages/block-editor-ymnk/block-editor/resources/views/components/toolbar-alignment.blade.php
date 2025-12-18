{{-- Toolbar: Text Alignment (Left, Center, Right, Justify) --}}
<template x-if="focusedBlockId === block.id">
    <div class="block-toolbar-section">
        {{-- Align Left --}}
        <button 
            class="block-toolbar-btn" 
            :class="{ 'active': block.attributes.alignment === 'left' || !block.attributes.alignment }"
            title="Alinhar à esquerda"
            @click="toggleAlignment(block.id, 'left')"
        >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="17" y1="10" x2="3" y2="10"></line>
                <line x1="21" y1="6" x2="3" y2="6"></line>
                <line x1="21" y1="14" x2="3" y2="14"></line>
                <line x1="17" y1="18" x2="3" y2="18"></line>
            </svg>
        </button>
        
        {{-- Align Center --}}
        <button 
            class="block-toolbar-btn" 
            :class="{ 'active': block.attributes.alignment === 'center' }"
            title="Centralizar"
            @click="toggleAlignment(block.id, 'center')"
        >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="10" x2="6" y2="10"></line>
                <line x1="21" y1="6" x2="3" y2="6"></line>
                <line x1="21" y1="14" x2="3" y2="14"></line>
                <line x1="18" y1="18" x2="6" y2="18"></line>
            </svg>
        </button>
        
        {{-- Align Right --}}
        <button 
            class="block-toolbar-btn" 
            :class="{ 'active': block.attributes.alignment === 'right' }"
            title="Alinhar à direita"
            @click="toggleAlignment(block.id, 'right')"
        >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="21" y1="10" x2="7" y2="10"></line>
                <line x1="21" y1="6" x2="3" y2="6"></line>
                <line x1="21" y1="14" x2="3" y2="14"></line>
                <line x1="21" y1="18" x2="7" y2="18"></line>
            </svg>
        </button>
        
        {{-- Align Justify --}}
        <button 
            class="block-toolbar-btn" 
            :class="{ 'active': block.attributes.alignment === 'justify' }"
            title="Justificar"
            @click="toggleAlignment(block.id, 'justify')"
        >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="21" y1="10" x2="3" y2="10"></line>
                <line x1="21" y1="6" x2="3" y2="6"></line>
                <line x1="21" y1="14" x2="3" y2="14"></line>
                <line x1="21" y1="18" x2="3" y2="18"></line>
            </svg>
        </button>
    </div>
</template>
