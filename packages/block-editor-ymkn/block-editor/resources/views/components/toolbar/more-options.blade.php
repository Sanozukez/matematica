{{-- Toolbar Section: More Options (Duplicate, Insert, Delete) --}}
<div class="block-toolbar-dropdown">
    <button class="block-toolbar-btn" title="Mais opções"
            @click="showMore = !showMore"
            @click.outside="showMore = false">
        <span class="material-icons">more_vert</span>
    </button>
    
    <div x-show="showMore" class="block-toolbar-dropdown-menu">
        <button class="block-toolbar-dropdown-item" @click="duplicateBlock(block.id); showMore = false">
            <span class="material-icons">content_copy</span>
            <span>Duplicar bloco</span>
        </button>
        <button class="block-toolbar-dropdown-item" @click="addBlock('paragraph', blocks.findIndex(b => b.id === block.id)); showMore = false">
            <span class="material-icons">add_circle_outline</span>
            <span>Inserir bloco antes</span>
        </button>
        <button class="block-toolbar-dropdown-item" @click="addBlock('paragraph', blocks.findIndex(b => b.id === block.id) + 1); showMore = false">
            <span class="material-icons">add_circle_outline</span>
            <span>Inserir bloco depois</span>
        </button>
        <div style="height: 1px; background: #3C3C3C; margin: 4px 0;"></div>
        <button class="block-toolbar-dropdown-item" @click="removeBlock(block.id); showMore = false" style="color: #FF6B6B;">
            <span class="material-icons">delete_outline</span>
            <span>Excluir bloco</span>
        </button>
    </div>
</div>
