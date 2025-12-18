{{-- Toolbar Section: Text Alignment Dropdown (Paragraph & Heading) --}}
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
