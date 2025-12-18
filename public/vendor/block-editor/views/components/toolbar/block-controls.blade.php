{{-- Toolbar Section: Block Controls (Type, Drag, Move Up/Down) --}}
<div class="block-toolbar-section">
    {{-- Ícone do Tipo do Bloco --}}
    <button class="block-toolbar-btn" title="Alterar tipo de bloco">
        <div class="block-toolbar-icon" x-html="blockTypes.find(t => t.type === block.type)?.icon || ''"></div>
    </button>

    {{-- Drag Handle --}}
    <button class="block-toolbar-btn" title="Arrastar para reordenar" disabled>
        <span class="material-icons">drag_indicator</span>
    </button>

    {{-- Move Up/Down --}}
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
