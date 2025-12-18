{{-- LaTeX Block Component --}}
<div 
    class="block-wrapper block-latex-wrapper" 
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
        <div class="block-latex">
            <div class="block-latex-editor">
                <div class="block-latex-label">
                    <span class="material-icons">functions</span>
                    <span>LaTeX</span>
                </div>
                <textarea 
                    class="block-latex-input"
                    :value="block.content"
                    @input="updateBlockContent(block.id, $event.target.value); debouncedSave();"
                    @focus="focusedBlockId = block.id"
                    placeholder="Digite a fórmula LaTeX... Ex: E = mc^2"
                    rows="3"
                ></textarea>
            </div>
            
            <div class="block-latex-preview" :class="(block.attributes?.mode || 'inline') === 'block' ? 'block-latex-display' : 'block-latex-inline'">
                <template x-if="block.content && block.content.trim() !== ''">
                    <div class="block-latex-rendered" x-html="renderLatex(block.content, block.attributes?.mode || 'inline')"></div>
                </template>
                <template x-if="!block.content || block.content.trim() === ''">
                    <div class="block-latex-placeholder">
                        <span class="material-icons">visibility_off</span>
                        <p>A visualização aparecerá aqui</p>
                    </div>
                </template>
            </div>
        </div>
    </div>
    
    <style>
        .block-latex-wrapper {
            position: relative;
            margin: 1.5rem 0;
            transition: all 0.1s ease;
        }
        
        .block-latex-wrapper:hover {
            outline: 1px solid #E0E0E0;
            outline-offset: -1px;
        }
        
        .block-latex-wrapper.block-focused {
            outline: 2px solid #007CBA;
            outline-offset: -2px;
        }
        
        .block-latex {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            background: #F8F9FA;
            border-radius: 8px;
            padding: 1rem;
        }
        
        .block-latex-editor {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .block-latex-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #6B7280;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .block-latex-label .material-icons {
            font-size: 18px;
        }
        
        .block-latex-input {
            width: 100%;
            padding: 0.75rem;
            background: white;
            border: 1px solid #CBD5E0;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
            line-height: 1.6;
            color: #1E1E1E;
            resize: vertical;
            outline: none;
            transition: border-color 0.2s;
        }
        
        .block-latex-input:focus {
            border-color: #007CBA;
        }
        
        .block-latex-input::placeholder {
            color: #9CA3AF;
        }
        
        .block-latex-preview {
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 1px solid #CBD5E0;
            border-radius: 6px;
            padding: 1rem;
            min-height: 100px;
        }
        
        .block-latex-inline {
            font-size: 1rem;
        }
        
        .block-latex-display {
            font-size: 1.25rem;
        }
        
        .block-latex-rendered {
            overflow-x: auto;
            max-width: 100%;
        }
        
        .block-latex-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            color: #9CA3AF;
        }
        
        .block-latex-placeholder .material-icons {
            font-size: 32px;
        }
        
        .block-latex-placeholder p {
            font-size: 0.875rem;
            margin: 0;
        }
    </style>
</div>
