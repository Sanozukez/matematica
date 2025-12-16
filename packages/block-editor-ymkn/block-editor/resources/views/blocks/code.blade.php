{{-- Code Block Component --}}
<div 
    class="block-wrapper" 
    :data-block-id="block.id"
    :class="{ 'block-focused': focusedBlockId === block.id }"
>
    {{-- Toolbar Universal --}}
    @include('block-editor-ymkn::components.block-toolbar')
    
    <div class="block-content">
        <div class="code-block-header">
            <span class="code-block-label">Código</span>
        </div>
        <pre class="block-code"><code 
            contenteditable="true"
            @input="updateBlockContent(block.id, $event.target.textContent)"
            @keydown.tab.prevent="insertTab($event)"
            @focus="focusedBlockId = block.id"
            x-init="$el.textContent = block.content"
            placeholder="// Digite o código..."
            spellcheck="false"
        ></code></pre>
    </div>
    
    <style>
        .code-block-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 1rem;
            background: #1E293B;
            border-radius: 6px 6px 0 0;
        }
        
        .code-block-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .block-code {
            margin: 0;
            padding: 1.5rem;
            background: #0F172A;
            border-radius: 0 0 6px 6px;
            overflow-x: auto;
        }
        
        .block-code code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.875rem;
            line-height: 1.7;
            color: #E2E8F0;
            outline: none;
            white-space: pre;
            display: block;
            min-height: 3rem;
        }
        
        .block-code code:empty:before {
            content: attr(placeholder);
            color: #64748B;
        }
    </style>
    
    <script>
        function insertTab(event) {
            const element = event.target;
            const selection = window.getSelection();
            const range = selection.getRangeAt(0);
            
            const tab = document.createTextNode('    '); // 4 espaços
            range.insertNode(tab);
            range.setStartAfter(tab);
            range.setEndAfter(tab);
            selection.removeAllRanges();
            selection.addRange(range);
        }
    </script>
</div>
