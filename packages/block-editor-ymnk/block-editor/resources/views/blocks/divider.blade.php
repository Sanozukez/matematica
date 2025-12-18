{{-- Divider Block Component --}}
<div 
    class="block-wrapper block-divider-wrapper" 
    :data-block-id="block.id"
    :class="{ 'block-focused': focusedBlockId === block.id }"
    @click="focusedBlockId = block.id"
    @dragover="handleDragOver($event, block.id)"
    @dragleave="handleDragLeave($event)"
    @drop="handleDrop($event, block.id)"
>
    {{-- Toolbar Universal --}}
    @include('block-editor-ymnk::components.block-toolbar')
    
    <hr class="block-divider" />
    
    <style>
        .block-divider-wrapper {
            padding: 1.5rem 0;
            cursor: pointer;
        }
        
        .block-divider {
            border: none;
            border-top: 2px solid #DCDCDE;
            margin: 0;
            transition: border-color 0.2s;
        }
        
        .block-divider-wrapper.block-focused .block-divider {
            border-top-color: #007CBA;
        }
        
        .block-divider-wrapper:hover .block-divider {
            border-top-color: #999;
        }
    </style>
</div>
