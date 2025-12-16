{{-- Toolbar Section: Text Formatting (Bold, Italic, Link) --}}
<div class="block-toolbar-section">
    {{-- Negrito --}}
    <button class="block-toolbar-btn" title="Negrito (Ctrl+B)"
            @click="applyFormatting(block.id, 'bold')">
        <span class="material-icons">format_bold</span>
    </button>
    
    {{-- Itálico --}}
    <button class="block-toolbar-btn" title="Itálico (Ctrl+I)"
            @click="applyFormatting(block.id, 'italic')">
        <span class="material-icons">format_italic</span>
    </button>
    
    {{-- Link --}}
    <div class="block-toolbar-dropdown">
        <button class="block-toolbar-btn" title="Inserir link (Ctrl+K)"
                @click="showLinkInput = !showLinkInput"
                @click.outside="showLinkInput = false">
            <span class="material-icons">link</span>
        </button>
        
        <div x-show="showLinkInput" class="block-toolbar-dropdown-menu" style="min-width: 280px;">
            <input type="url" 
                   x-model="linkUrl" 
                   placeholder="https://exemplo.com"
                   @keydown.enter="insertLink(block.id, linkUrl); linkUrl = ''; showLinkInput = false"
                   style="width: 100%; padding: 8px; background: #2C2C2C; border: 1px solid #3C3C3C; border-radius: 4px; color: #E0E0E0; font-size: 13px; margin-bottom: 8px;">
            <button class="block-toolbar-dropdown-item" @click="insertLink(block.id, linkUrl); linkUrl = ''; showLinkInput = false">
                <span class="material-icons">done</span>
                <span>Inserir link</span>
            </button>
        </div>
    </div>
</div>
