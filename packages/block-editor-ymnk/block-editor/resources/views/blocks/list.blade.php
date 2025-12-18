{{-- List Block Component --}}
<div 
    class="block-wrapper block-list-wrapper" 
    :data-block-id="block.id"
    :class="{ 'block-focused': focusedBlockId === block.id }"
    @click="focusedBlockId = block.id"
    @dragover="handleDragOver($event, block.id)"
    @dragleave="handleDragLeave($event)"
    @drop="handleDrop($event, block.id)"
>
    {{-- Toolbar Universal --}}
    @include('block-editor-ymnk::components.block-toolbar')
    
    <div class="block-content">
        <template x-if="(block.attributes?.listType || 'ul') === 'ul'">
            <ul class="block-list block-list-unordered">
                <template x-for="(item, index) in (block.content || [''])" :key="index">
                    <li 
                        class="block-list-item"
                        contenteditable="true"
                        @input="updateListItem(block.id, index, $event.target.textContent)"
                        @keydown.enter.prevent="addListItem(block.id, index)"
                        @keydown.backspace="handleListBackspace($event, block.id, index)"
                        @focus="focusedBlockId = block.id"
                        x-html="item"
                    ></li>
                </template>
            </ul>
        </template>
        
        <template x-if="(block.attributes?.listType || 'ul') === 'ol'">
            <ol class="block-list block-list-ordered">
                <template x-for="(item, index) in (block.content || [''])" :key="index">
                    <li 
                        class="block-list-item"
                        contenteditable="true"
                        @input="updateListItem(block.id, index, $event.target.textContent)"
                        @keydown.enter.prevent="addListItem(block.id, index)"
                        @keydown.backspace="handleListBackspace($event, block.id, index)"
                        @focus="focusedBlockId = block.id"
                        x-html="item"
                    ></li>
                </template>
            </ol>
        </template>
    </div>
    
    <style>
        .block-list-wrapper {
            position: relative;
            margin: 1rem 0;
            transition: all 0.1s ease;
        }
        
        .block-list-wrapper:hover {
            outline: 1px solid #E0E0E0;
            outline-offset: -1px;
        }
        
        .block-list-wrapper.block-focused {
            outline: 2px solid #007CBA;
            outline-offset: -2px;
        }
        
        .block-list {
            padding: 0.75rem 1rem;
            padding-left: 2.5rem;
            margin: 0;
        }
        
        .block-list-unordered {
            list-style-type: disc;
        }
        
        .block-list-ordered {
            list-style-type: decimal;
        }
        
        .block-list-item {
            font-size: 1rem;
            line-height: 1.8;
            color: #1E1E1E;
            margin-bottom: 0.5rem;
            outline: none;
        }
        
        .block-list-item:last-child {
            margin-bottom: 0;
        }
        
        .block-list-item:empty:before {
            content: 'Digite um item...';
            color: #9CA3AF;
            pointer-events: none;
        }
    </style>
</div>
