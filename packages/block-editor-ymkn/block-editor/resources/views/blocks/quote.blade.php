{{-- Quote Block Component --}}
<div 
    class="block-wrapper" 
    :data-block-id="block.id"
    :class="{ 'block-focused': focusedBlockId === block.id }"
>
    <div class="block-content">
        <blockquote class="block-quote">
            <div 
                class="block-quote-text"
                contenteditable="true"
                @input="updateBlockContent(block.id, $event.target.textContent)"
                @keydown.enter="handleEnter($event, block.id)"
                @keydown.backspace="handleBackspace($event, block.id)"
                @focus="focusedBlockId = block.id"
                x-init="$el.textContent = block.content"
                placeholder="Digite a citação..."
            ></div>
        </blockquote>
    </div>
    
    <style>
        .block-quote {
            margin: 0;
            padding: 1rem 1.5rem;
            border-left: 4px solid #007CBA;
            background: #F9FAFB;
            font-style: italic;
        }
        
        .block-quote-text {
            font-size: 1.125rem;
            line-height: 1.8;
            color: #374151;
            outline: none;
            white-space: pre-wrap;
            word-wrap: break-word;
            min-height: 2rem;
        }
        
        .block-quote-text:empty:before {
            content: attr(placeholder);
            color: #9CA3AF;
            font-style: normal;
        }
    </style>
</div>
