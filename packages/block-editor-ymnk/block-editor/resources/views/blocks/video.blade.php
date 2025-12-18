{{-- Video Block Component --}}
<div 
    class="block-wrapper block-video-wrapper" 
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
            <div class="block-video-placeholder">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
                <p>Cole a URL do YouTube ou Vimeo</p>
                <input 
                    type="url" 
                    placeholder="https://www.youtube.com/watch?v=..."
                    @keydown.enter="updateBlockContent(block.id, $event.target.value)"
                    class="block-video-input"
                >
            </div>
        </template>
        
        <template x-if="block.content && block.content !== ''">
            <div class="block-video-container">
                <div class="block-video-embed" x-html="getVideoEmbed(block.content)"></div>
                <button class="block-video-remove" @click="updateBlockContent(block.id, '')">
                    <span class="material-icons">close</span>
                </button>
            </div>
        </template>
    </div>
    
    <style>
        .block-video-wrapper {
            position: relative;
            margin: 1.5rem 0;
            transition: all 0.1s ease;
        }
        
        .block-video-wrapper:hover {
            outline: 1px solid #E0E0E0;
            outline-offset: -1px;
        }
        
        .block-video-wrapper.block-focused {
            outline: 2px solid #007CBA;
            outline-offset: -2px;
        }
        
        .block-video-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            background: #F8F9FA;
            border: 2px dashed #CBD5E0;
            border-radius: 8px;
            text-align: center;
        }
        
        .block-video-placeholder svg {
            width: 48px;
            height: 48px;
            color: #9CA3AF;
            margin-bottom: 1rem;
        }
        
        .block-video-placeholder p {
            color: #6B7280;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        
        .block-video-input {
            width: 100%;
            max-width: 500px;
            padding: 0.75rem 1rem;
            border: 1px solid #CBD5E0;
            border-radius: 6px;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s;
        }
        
        .block-video-input:focus {
            border-color: #007CBA;
        }
        
        .block-video-container {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
            border-radius: 8px;
            background: #000;
        }
        
        .block-video-embed {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        .block-video-embed iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .block-video-remove {
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
            z-index: 10;
        }
        
        .block-video-container:hover .block-video-remove {
            opacity: 1;
        }
        
        .block-video-remove:hover {
            background: rgba(0, 0, 0, 0.9);
        }
        
        .block-video-remove .material-icons {
            font-size: 18px;
        }
    </style>
</div>
