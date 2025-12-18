{{-- Alert Block Component --}}
<div 
    class="block-wrapper block-alert-wrapper" 
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
        <div 
            class="block-alert"
            :class="'block-alert-' + (block.attributes?.type || 'info')"
        >
            <div class="block-alert-icon">
                <template x-if="(block.attributes?.type || 'info') === 'info'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                    </svg>
                </template>
                <template x-if="(block.attributes?.type || 'info') === 'warning'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </template>
                <template x-if="(block.attributes?.type || 'info') === 'error'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </template>
                <template x-if="(block.attributes?.type || 'info') === 'success'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </template>
            </div>
            <div 
                class="block-alert-content"
                contenteditable="true"
                @input="updateBlockContent(block.id, $event.target.innerHTML); debouncedSave();"
                @focus="focusedBlockId = block.id"
                x-init="if (block.content) { $el.innerHTML = block.content; }"
                placeholder="Digite uma mensagem importante..."
            ></div>
        </div>
    </div>
    
    <style>
        .block-alert-wrapper {
            position: relative;
            margin: 1rem 0;
            transition: all 0.1s ease;
        }
        
        .block-alert {
            display: flex;
            gap: 1rem;
            padding: 1rem 1.25rem;
            border-radius: 8px;
            border-left: 4px solid;
        }
        
        .block-alert-info {
            background: #EFF6FF;
            border-left-color: #3B82F6;
            color: #1E40AF;
        }
        
        .block-alert-warning {
            background: #FFFBEB;
            border-left-color: #F59E0B;
            color: #92400E;
        }
        
        .block-alert-error {
            background: #FEF2F2;
            border-left-color: #EF4444;
            color: #991B1B;
        }
        
        .block-alert-success {
            background: #F0FDF4;
            border-left-color: #10B981;
            color: #065F46;
        }
        
        .block-alert-icon {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
        }
        
        .block-alert-icon svg {
            width: 24px;
            height: 24px;
        }
        
        .block-alert-content {
            flex: 1;
            font-size: 0.9375rem;
            line-height: 1.6;
            outline: none;
            min-height: 1.5rem;
        }
        
        .block-alert-content:empty:before {
            content: attr(placeholder);
            opacity: 0.5;
            pointer-events: none;
        }
        
        .block-alert-wrapper:hover {
            outline: 1px solid #E0E0E0;
            outline-offset: -1px;
        }
        
        .block-alert-wrapper.block-focused {
            outline: 2px solid #007CBA;
            outline-offset: -2px;
        }
    </style>
</div>
