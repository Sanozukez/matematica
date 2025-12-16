{{-- Heading Block Component --}}
<div 
    class="block-wrapper" 
    :data-block-id="block.id"
    :class="{ 'block-focused': focusedBlockId === block.id }"
>
    {{-- Toolbar Universal --}}
    @include('block-editor-ymkn::components.block-toolbar')
    
    <div class="block-content">
        {{-- Heading Editable --}}
        <div 
            class="block-heading"
            :class="'heading-level-' + (block.attributes.level || 2)"
            contenteditable="true"
            @input="updateBlockContent(block.id, $event.target.textContent)"
            @keydown.enter="handleEnter($event, block.id)"
            @keydown.backspace="handleBackspace($event, block.id)"
            @focus="focusedBlockId = block.id"
            x-init="$el.textContent = block.content"
            placeholder="Digite o título..."
        ></div>
    </div>
    
    <style>
        /* Floating Toolbar - estilo WordPress */
        .heading-floating-toolbar {
            position: absolute;
            top: -48px;
            left: 0;
            display: flex;
            gap: 2px;
            background: #1E1E1E;
            border-radius: 4px;
            padding: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 10;
        }
        
        .heading-level-btn-toolbar {
            padding: 6px 10px;
            background: transparent;
            border: none;
            border-radius: 3px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #FFFFFF;
            cursor: pointer;
            transition: background-color 0.15s;
            min-width: 32px;
        }
        
        .heading-level-btn-toolbar:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .heading-level-btn-toolbar.active {
            background: #007CBA;
            color: #FFFFFF;
        }
        
        .block-heading {
            font-weight: 600;
            color: #1E1E1E;
            padding: 0.75rem 1rem;
            min-height: 2.5rem;
            outline: none;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        
        .heading-level-1 { font-size: 2.5rem; line-height: 1.2; }
        .heading-level-2 { font-size: 2rem; line-height: 1.3; }
        .heading-level-3 { font-size: 1.75rem; line-height: 1.4; }
        .heading-level-4 { font-size: 1.5rem; line-height: 1.5; }
        .heading-level-5 { font-size: 1.25rem; line-height: 1.6; }
        .heading-level-6 { font-size: 1.125rem; line-height: 1.6; }
        
        .block-heading:empty:before {
            content: attr(placeholder);
            color: #9CA3AF;
        }
        
        .block-heading:focus {
            background: rgba(0, 124, 186, 0.02);
        }
    </style>
</div>
