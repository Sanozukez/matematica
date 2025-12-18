{{-- Paragraph Block Component --}}
<div 
    class="block-wrapper" 
    :data-block-id="block.id"
    :class="{ 'block-focused': focusedBlockId === block.id }"
    @click="focusedBlockId = block.id"
    @keydown.delete.window="if (focusedBlockId === block.id) { removeBlock(block.id) }"
    @dragover="handleDragOver($event, block.id)"
    @dragleave="handleDragLeave($event)"
    @drop="handleDrop($event, block.id)"
    tabindex="0">
    {{-- Toolbar Universal Flutuante --}}
    @include('block-editor-ymnk::components.block-toolbar')
    
    <div class="block-content">
        <div 
            class="block-paragraph"
            contenteditable="true"
            @input="handleContentInput($event, block.id)"
            @keydown.enter="handleEnter($event, block.id)"
            @keydown.up.prevent="navigateSlashMenu('up', block.id)"
            @keydown.down.prevent="navigateSlashMenu('down', block.id)"
            @keydown.backspace="handleBackspace($event, block.id)"
            @focus="focusedBlockId = block.id"
            x-init="if (block.content) { $el.innerHTML = block.content; }"
            placeholder="Digite / para comandos"
        ></div>
    </div>

    <style>
        /* Paragraph Block Styles */
        .block-wrapper {
            position: relative;
            margin: 0;
            transition: all 0.1s ease;
        }
        
        .block-wrapper:hover {
            outline: 1px solid #E0E0E0;
            outline-offset: -1px;
        }
        
        .block-wrapper.block-focused {
            outline: 2px solid #007CBA;
            outline-offset: -2px;
        }
        
        .block-content {
            position: relative;
        }
        
        .block-paragraph {
            font-size: 1rem;
            line-height: 1.8;
            color: #1E1E1E;
            padding: 0.75rem 1rem;
            min-height: 2.5rem;
            outline: none;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        
        .block-paragraph:empty:before {
            content: attr(placeholder);
            color: #9CA3AF;
            pointer-events: none;
        }
        
        .block-paragraph:focus {
            background: rgba(0, 124, 186, 0.02);
        }
    </style>
</div>
