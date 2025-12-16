{{-- Heading Block Component --}}
<div 
    class="block-wrapper" 
    :data-block-id="block.id"
    :class="{ 'block-focused': focusedBlockId === block.id }"
>
    <div class="block-content">
        {{-- Level Selector --}}
        <div class="heading-level-selector" x-show="focusedBlockId === block.id">
            <template x-for="level in [1,2,3,4,5,6]" :key="level">
                <button 
                    class="heading-level-btn"
                    :class="{ 'active': (block.attributes.level || 2) === level }"
                    @click="updateBlockAttributes(block.id, { level: level })"
                    x-text="'H' + level"
                ></button>
            </template>
        </div>
        
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
        .heading-level-selector {
            display: flex;
            gap: 0.25rem;
            margin-bottom: 0.5rem;
            padding: 0.5rem;
            background: #F9FAFB;
            border-radius: 4px;
        }
        
        .heading-level-btn {
            padding: 0.25rem 0.75rem;
            background: #FFFFFF;
            border: 1px solid #DCDCDE;
            border-radius: 3px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .heading-level-btn:hover {
            background: #F0F0F0;
        }
        
        .heading-level-btn.active {
            background: #007CBA;
            color: #FFFFFF;
            border-color: #007CBA;
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
