{{-- Toolbar: Formatting Buttons (Bold, Italic, Underline, Strike) --}}
<template x-if="focusedBlockId === block.id">
    <div class="block-toolbar-section">
        {{-- Bold --}}
        <button 
            class="block-toolbar-btn" 
            title="Negrito (Ctrl+B)"
            @click="applyFormat('bold')"
        >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"></path>
                <path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"></path>
            </svg>
        </button>
        
        {{-- Italic --}}
        <button 
            class="block-toolbar-btn" 
            title="Itálico (Ctrl+I)"
            @click="applyFormat('italic')"
        >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="4" x2="10" y2="4"></line>
                <line x1="14" y1="20" x2="5" y2="20"></line>
                <line x1="15" y1="4" x2="9" y2="20"></line>
            </svg>
        </button>
        
        {{-- Underline --}}
        <button 
            class="block-toolbar-btn" 
            title="Sublinhado (Ctrl+U)"
            @click="applyFormat('underline')"
        >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 3v7a6 6 0 0 0 6 6 6 6 0 0 0 6-6V3"></path>
                <line x1="4" y1="21" x2="20" y2="21"></line>
            </svg>
        </button>
        
        {{-- Strikethrough --}}
        <button 
            class="block-toolbar-btn" 
            title="Tachado"
            @click="applyFormat('strikeThrough')"
        >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17.3 4.9c-2.3-.6-4.4-1-6.2-.9-2.7 0-5.3.7-5.3 3.6 0 1.5 1.8 3.3 3.6 3.9h.2m8.2 3.7c.3.4.4.8.4 1.3 0 2.9-2.7 3.6-6.2 3.6-2.3 0-4.4-.3-6.2-.9M4 11.5h16"></path>
            </svg>
        </button>
    </div>
</template>
