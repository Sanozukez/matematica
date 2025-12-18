{{-- Image Block Component --}}
<div 
    class="block-wrapper block-image-wrapper" 
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
        <template x-if="!block.content || block.content === ''">
            <div class="block-image-placeholder">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                <p>Clique para adicionar imagem</p>
                <input type="file" accept="image/*" @change="handleImageUpload($event, block.id)" style="display: none;" x-ref="imageInput">
                <button class="block-image-upload-btn" @click="$refs.imageInput.click()">
                    Escolher arquivo
                </button>
            </div>
        </template>
        
        <template x-if="block.content && block.content !== ''">
            <div class="block-image-container" :class="block.attributes?.alignment || 'center'">
                <img :src="block.content" :alt="block.attributes?.alt || 'Imagem'" class="block-image">
                <button class="block-image-remove" @click="updateBlockContent(block.id, ''); updateBlockAttributes(block.id, { alt: '' })">
                    <span class="material-icons">close</span>
                </button>
            </div>
        </template>
    </div>
    
    <style>
        .block-image-wrapper {
            position: relative;
            margin: 1.5rem 0;
            transition: all 0.1s ease;
        }
        
        .block-image-wrapper:hover {
            outline: 1px solid #E0E0E0;
            outline-offset: -1px;
        }
        
        .block-image-wrapper.block-focused {
            outline: 2px solid #007CBA;
            outline-offset: -2px;
        }
        
        .block-image-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            background: #F8F9FA;
            border: 2px dashed #CBD5E0;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .block-image-placeholder:hover {
            background: #F1F3F5;
            border-color: #A0AEC0;
        }
        
        .block-image-placeholder svg {
            width: 48px;
            height: 48px;
            color: #9CA3AF;
            margin-bottom: 1rem;
        }
        
        .block-image-placeholder p {
            color: #6B7280;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        
        .block-image-upload-btn {
            padding: 0.5rem 1.5rem;
            background: #007CBA;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .block-image-upload-btn:hover {
            background: #006BA1;
        }
        
        .block-image-container {
            position: relative;
            display: flex;
        }
        
        .block-image-container.left {
            justify-content: flex-start;
        }
        
        .block-image-container.center {
            justify-content: center;
        }
        
        .block-image-container.right {
            justify-content: flex-end;
        }
        
        .block-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .block-image-remove {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            width: 32px;
            height: 32px;
            background: rgba(0, 0, 0, 0.7);
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.2s;
            color: white;
        }
        
        .block-image-container:hover .block-image-remove {
            opacity: 1;
        }
        
        .block-image-remove:hover {
            background: rgba(0, 0, 0, 0.9);
        }
        
        .block-image-remove .material-icons {
            font-size: 18px;
        }
    </style>
</div>
