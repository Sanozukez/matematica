{{-- Heading Block Component --}}
<div 
    class="block-wrapper" 
    :data-block-id="block.id"
    :class="{ 'block-focused': focusedBlockId === block.id }"
>
    {{-- Floating Toolbar (estilo WordPress) --}}
    <div 
        class="heading-floating-toolbar" 
        x-show="focusedBlockId === block.id"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
    >
        <template x-for="level in [1,2,3,4,5,6]" :key="level">
            <button 
                class="heading-level-btn-toolbar"
                :class="{ 'active': (block.attributes.level || 2) === level }"
                @click="updateBlockAttributes(block.id, { level: level })"
                x-text="'H' + level"
                :title="'Título nível ' + level"
            ></button>
        </template>
    </div>
    
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
