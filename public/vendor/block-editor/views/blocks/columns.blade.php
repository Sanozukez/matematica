{{-- Columns Block Component --}}
<div 
    class="block-wrapper block-columns-wrapper" 
    :data-block-id="block.id"
    :class="{ 'block-focused': focusedBlockId === block.id }"
    @click="focusedBlockId = block.id"
    @dragover="handleDragOver($event, block.id)"
    @dragleave="handleDragLeave($event)"
    @drop="handleDrop($event, block.id)"
>
    {{-- Toolbar Universal --}}
    @include('block-editor-ymnk::components.block-toolbar')
    
        <div class="block-content columns-container" 
             :class="'columns-' + (block.attributes?.columnCount || 2)"
             @click.self="focusedBlockId = block.id; focusedColumnIndex = null; focusedInnerBlockId = null">
            <template x-for="(column, colIndex) in getColumns(block)" :key="colIndex">
              <div class="column" 
                  :class="{ 'column-focused': focusedColumnIndex === colIndex && focusedBlockId === block.id }"
                  @click.stop="focusedBlockId = block.id; focusedColumnIndex = colIndex; focusedInnerBlockId = null">
                
                {{-- Empty state da coluna --}}
                <template x-if="!column.blocks || column.blocks.length === 0">
                    <div class="column-empty-state" @click="focusedBlockId = block.id; focusedColumnIndex = colIndex; focusedInnerBlockId = null; addInnerBlock(block.id, colIndex, 'paragraph')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Clique para adicionar bloco</span>
                    </div>
                </template>
                
                {{-- Inserter full-width com dropdown (todos os blocos, exceto columns) --}}
                <div class="column-inserter" x-data="{ open: false }" x-show="focusedBlockId === block.id" @click.outside="open = false">
                    <button type="button" class="column-inserter-btn" @click.stop="open = !open">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Adicionar bloco
                    </button>

                    <div class="column-inserter-menu" x-show="open" x-transition.opacity>
                        <template x-for="bt in blockTypes" :key="bt.type">
                            <button
                                type="button"
                                class="column-inserter-item"
                                x-show="bt.type !== 'columns'"
                                @click.prevent="open = false; addInnerBlock(block.id, colIndex, bt.type)"
                            >
                                <span class="column-inserter-icon" x-html="bt.icon"></span>
                                <div>
                                    <div x-text="bt.label"></div>
                                    <div class="column-inserter-desc" x-text="bt.description || ''"></div>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Blocos dentro da coluna (todos os tipos, exceto columns) --}}
                <template x-for="(innerBlock, blockIndex) in (column.blocks || [])" :key="innerBlock.id">
                    <div class="column-inner-block" 
                         :data-inner-block-id="innerBlock.id"
                         :class="{ 'inner-block-focused': focusedInnerBlockId === innerBlock.id && focusedBlockId === block.id }"
                         @click.stop="focusInnerBlock(block.id, colIndex, innerBlock.id)"
                         @keydown.delete="removeInnerBlock(block.id, colIndex, innerBlock.id)"
                         @keydown.backspace.ctrl="removeInnerBlock(block.id, colIndex, innerBlock.id)"
                         tabindex="0">
                        <template x-if="innerBlock.type === 'paragraph'">
                            <div class="column-paragraph" contenteditable="true"
                                 dir="ltr"
                                 @input="updateInnerBlockContent(block.id, colIndex, innerBlock.id, $event.target.innerHTML)"
                                 @keydown.enter="handleInnerEnter($event, block.id, colIndex, innerBlock.id)"
                                 @keydown.backspace="handleInnerBackspace($event, block.id, colIndex, innerBlock.id)"
                                 @focus="focusInnerBlock(block.id, colIndex, innerBlock.id)"
                                 x-init="$el.innerHTML = innerBlock.content || ''"
                                 placeholder="Digite texto...">
                            </div>
                        </template>
                        <template x-if="innerBlock.type === 'heading'">
                            <div class="column-heading heading-level-3" contenteditable="true"
                                 dir="ltr"
                                 @input="updateInnerBlockContent(block.id, colIndex, innerBlock.id, $event.target.innerHTML)"
                                 @keydown.enter="handleInnerEnter($event, block.id, colIndex, innerBlock.id)"
                                 @keydown.backspace="handleInnerBackspace($event, block.id, colIndex, innerBlock.id)"
                                 @focus="focusInnerBlock(block.id, colIndex, innerBlock.id)"
                                 x-init="$el.innerHTML = innerBlock.content || ''"
                                 placeholder="Digite o título...">
                            </div>
                        </template>
                        <template x-if="innerBlock.type === 'quote'">
                            <div class="column-quote" contenteditable="true"
                                 dir="ltr"
                                 @input="updateInnerBlockContent(block.id, colIndex, innerBlock.id, $event.target.innerHTML)"
                                 @keydown.enter="handleInnerEnter($event, block.id, colIndex, innerBlock.id)"
                                 @keydown.backspace="handleInnerBackspace($event, block.id, colIndex, innerBlock.id)"
                                 @focus="focusInnerBlock(block.id, colIndex, innerBlock.id)"
                                 x-init="$el.innerHTML = innerBlock.content || ''"
                                 placeholder="Digite a citação...">
                            </div>
                        </template>
                        <template x-if="innerBlock.type === 'list'">
                            <div class="column-list" contenteditable="true"
                                 dir="ltr"
                                 @input="updateInnerBlockContent(block.id, colIndex, innerBlock.id, $event.target.innerHTML)"
                                 @keydown.enter="handleInnerEnter($event, block.id, colIndex, innerBlock.id)"
                                 @keydown.backspace="handleInnerBackspace($event, block.id, colIndex, innerBlock.id)"
                                 @focus="focusInnerBlock(block.id, colIndex, innerBlock.id)"
                                 x-init="$el.innerHTML = innerBlock.content || ''"
                                 placeholder="Itens da lista (separe com Enter)">
                            </div>
                        </template>
                        <template x-if="innerBlock.type === 'code'">
                            <div class="column-code-wrapper">
                                <div class="column-code-header">
                                    <span class="column-code-label">Código</span>
                                </div>
                                <pre class="column-code-block"><code 
                                    contenteditable="true"
                                    @input="updateInnerBlockContent(block.id, colIndex, innerBlock.id, $event.target.textContent)"
                                    @keydown.tab.prevent="insertTab($event)"
                                    @keydown.enter="handleInnerEnter($event, block.id, colIndex, innerBlock.id)"
                                    @keydown.backspace="handleInnerBackspace($event, block.id, colIndex, innerBlock.id)"
                                    @focus="focusInnerBlock(block.id, colIndex, innerBlock.id)"
                                    x-init="$el.textContent = innerBlock.content || ''"
                                    placeholder="// Digite o código..."
                                    spellcheck="false"
                                ></code></pre>
                            </div>
                        </template>
                        <template x-if="innerBlock.type === 'alert'">
                            <div class="column-alert-wrapper">
                                <div class="column-alert" :class="'column-alert-' + (innerBlock.attributes?.type || 'info')">
                                    <div class="column-alert-icon">
                                        <template x-if="(innerBlock.attributes?.type || 'info') === 'info'">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                            </svg>
                                        </template>
                                        <template x-if="(innerBlock.attributes?.type || 'info') === 'warning'">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                            </svg>
                                        </template>
                                        <template x-if="(innerBlock.attributes?.type || 'info') === 'error'">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                            </svg>
                                        </template>
                                        <template x-if="(innerBlock.attributes?.type || 'info') === 'success'">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </template>
                                    </div>
                                    <div 
                                        class="column-alert-content"
                                        contenteditable="true"
                                        @input="updateInnerBlockContent(block.id, colIndex, innerBlock.id, $event.target.innerHTML)"
                                        @keydown.enter="handleInnerEnter($event, block.id, colIndex, innerBlock.id)"
                                        @keydown.backspace="handleInnerBackspace($event, block.id, colIndex, innerBlock.id)"
                                        @focus="focusInnerBlock(block.id, colIndex, innerBlock.id)"
                                        x-init="$el.innerHTML = innerBlock.content || ''"
                                        placeholder="Digite uma mensagem importante..."
                                    ></div>
                                </div>
                            </div>
                        </template>
                        <template x-if="innerBlock.type === 'latex'">
                            <div class="column-latex-wrapper">
                                <div class="column-latex-header">
                                    <span class="column-latex-label">LaTeX</span>
                                </div>
                                <div class="column-latex-content"
                                     contenteditable="true"
                                     dir="ltr"
                                     @input="updateInnerBlockContent(block.id, colIndex, innerBlock.id, $event.target.innerHTML)"
                                     @keydown.enter="handleInnerEnter($event, block.id, colIndex, innerBlock.id)"
                                     @keydown.backspace="handleInnerBackspace($event, block.id, colIndex, innerBlock.id)"
                                     @focus="focusInnerBlock(block.id, colIndex, innerBlock.id)"
                                     x-init="$el.innerHTML = innerBlock.content || ''"
                                     placeholder="Digite equações LaTeX...">
                                </div>
                            </div>
                        </template>
                        <template x-if="innerBlock.type === 'image'">
                            <div class="column-image-wrapper" @click.stop="focusInnerBlock(block.id, colIndex, innerBlock.id)">
                                <template x-if="!innerBlock.content || innerBlock.content === ''">
                                    <div class="column-image-placeholder">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                        </svg>
                                        <p>Clique para adicionar imagem</p>
                                        <input type="file" accept="image/*" @change="updateInnerBlockContent(block.id, colIndex, innerBlock.id, $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : '')" style="display: none;" :id="'imageInput_' + innerBlock.id">
                                        <button class="column-image-upload-btn" @click="document.getElementById('imageInput_' + innerBlock.id).click()">
                                            Escolher arquivo
                                        </button>
                                    </div>
                                </template>
                                <template x-if="innerBlock.content && innerBlock.content !== ''">
                                    <div class="column-image-container">
                                        <img :src="innerBlock.content" :alt="innerBlock.attributes?.alt || 'Imagem'" />
                                        <button class="column-image-remove" @click.stop="updateInnerBlockContent(block.id, colIndex, innerBlock.id, '')">
                                            ×
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="innerBlock.type === 'video'">
                            <div class="column-video-wrapper" @click.stop="focusInnerBlock(block.id, colIndex, innerBlock.id)">
                                <template x-if="!innerBlock.content || innerBlock.content === ''">
                                    <div class="column-video-placeholder">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5l4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                                        </svg>
                                        <p>Cole URL do vídeo (YouTube, Vimeo, etc)</p>
                                        <input type="url" class="column-video-input" placeholder="Ex: https://youtube.com/embed/..."
                                               @keydown.enter="updateInnerBlockContent(block.id, colIndex, innerBlock.id, $event.target.value)"
                                               @focus="focusInnerBlock(block.id, colIndex, innerBlock.id)">
                                    </div>
                                </template>
                                <template x-if="innerBlock.content && innerBlock.content !== ''">
                                    <div class="column-video-container">
                                        <iframe :src="innerBlock.content" frameborder="0" allowfullscreen></iframe>
                                        <button class="column-video-remove" @click.stop="updateInnerBlockContent(block.id, colIndex, innerBlock.id, '')">
                                            ×
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="innerBlock.type === 'table'">
                            <div class="column-table-wrapper">
                                <div class="column-table-container">
                                    <table class="column-table">
                                        <thead x-show="innerBlock.attributes?.hasHeader !== false">
                                            <tr>
                                                <template x-for="(cell, colIndex) in (innerBlock.content?.[0] || ['', '', ''])" :key="'header-' + colIndex">
                                                    <th 
                                                        class="column-table-cell column-table-header"
                                                        contenteditable="true"
                                                        @input="updateInnerBlockContent(block.id, colIndex, innerBlock.id, $event.target.textContent)"
                                                        @focus="focusInnerBlock(block.id, colIndex, innerBlock.id)"
                                                        x-text="cell"
                                                    ></th>
                                                </template>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(row, rowIndex) in (innerBlock.content?.slice(1) || [['', '', '']])" :key="'row-' + rowIndex">
                                                <tr>
                                                    <template x-for="(cell, cellIndex) in row" :key="'cell-' + rowIndex + '-' + cellIndex">
                                                        <td 
                                                            class="column-table-cell"
                                                            contenteditable="true"
                                                            @input="updateInnerBlockContent(block.id, rowIndex + 1, cellIndex, $event.target.textContent)"
                                                            @focus="focusInnerBlock(block.id, colIndex, innerBlock.id)"
                                                            x-text="cell"
                                                        ></td>
                                                    </template>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </template>
    </div>

    <style>
        .block-columns-wrapper {
            position: relative;
            margin: 1.5rem 0;
        }

        .columns-toolbar-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
        }
        
        .columns-container {
            display: grid;
            gap: 1.5rem;
            min-height: 100px;
        }
        
        .columns-2 { grid-template-columns: repeat(2, 1fr); }
        .columns-3 { grid-template-columns: repeat(3, 1fr); }
        .columns-4 { grid-template-columns: repeat(4, 1fr); }
        
        .column {
            border: 1px dashed #E0E0E0;
            border-radius: 4px;
            padding: 1rem;
            min-height: 100px;
            transition: all 0.2s;
            background: #FAFAFA;
        }
        
        .column:hover {
            border-color: #007CBA;
            background: white;
        }
        
        .column-focused {
            border-color: #007CBA;
            border-style: solid;
            background: white;
            box-shadow: 0 0 0 2px rgba(0, 124, 186, 0.1);
        }
        
        .column-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 2rem;
            color: #999;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .column-empty-state:hover {
            color: #007CBA;
        }
        
        .column-empty-state svg {
            width: 32px;
            height: 32px;
        }
        
        .column-empty-state span {
            font-size: 14px;
        }
        
        .column-inner-block {
            margin-bottom: 0.5rem;
            position: relative;
            outline: 2px solid transparent;
            outline-offset: 2px;
            transition: outline 0.15s ease;
            border-radius: 4px;
        }
        
        .column-inner-block:hover {
            outline: 1px solid #E0E0E0;
        }
        
        .column-inner-block.inner-block-focused {
            outline: 2px solid #007CBA;
            outline-offset: 2px;
        }
        
        .column-inner-block:last-child {
            margin-bottom: 0;
        }

        .column-inserter {
            position: relative;
            margin-bottom: 10px;
        }

        .column-inserter-btn {
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px 12px;
            border: 1px dashed #C7CDD4;
            border-radius: 6px;
            background: #F8FAFC;
            cursor: pointer;
            font-weight: 600;
            color: #007CBA;
        }

        .column-inserter-btn:hover {
            background: #E6F3FB;
            border-color: #007CBA;
        }

        .column-inserter-menu {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            margin-top: 6px;
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
            max-height: 260px;
            overflow-y: auto;
            z-index: 50;
        }

        .column-inserter-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            background: white;
            border: none;
            cursor: pointer;
            text-align: left;
        }

        .column-inserter-desc {
            font-size: 12px;
            color: #6B7280;
        }

        .column-inserter-item:hover {
            background: #F0F7FA;
        }

        .column-inserter-icon svg {
            width: 20px;
            height: 20px;
            color: #007CBA;
        }

        .column-paragraph {
            padding: 0.5rem 0.75rem;
            min-height: 2.2rem;
            outline: none;
            background: white;
            border-radius: 4px;
            border: 1px solid transparent;
            direction: ltr;
            text-align: left;
        }

        .column-paragraph:focus {
            border-color: #007CBA;
            box-shadow: 0 0 0 2px rgba(0, 124, 186, 0.1);
        }

        .column-paragraph:empty:before {
            content: attr(placeholder);
            color: #9CA3AF;
        }

        .column-heading {
            padding: 0.5rem 0.75rem;
            min-height: 2.2rem;
            outline: none;
            background: white;
            border-radius: 4px;
            border: 1px solid transparent;
            font-weight: 600;
            direction: ltr;
            text-align: left;
        }

        .column-heading.heading-level-3 { font-size: 1.25rem; }

        .column-heading:focus {
            border-color: #007CBA;
            box-shadow: 0 0 0 2px rgba(0, 124, 186, 0.1);
        }

        .column-heading:empty:before {
            content: attr(placeholder);
            color: #9CA3AF;
        }

        .column-quote,
        .column-list,
        .column-alert,
        .column-latex,
        .column-table {
            padding: 0.5rem 0.75rem;
            min-height: 2.2rem;
            outline: none;
            background: white;
            border-radius: 4px;
            border: 1px solid transparent;
            direction: ltr;
            text-align: left;
        }

        .column-quote { border-left: 4px solid #007CBA; background: #F7FAFC; }
        .column-list { list-style: disc; }
        .column-code { background: #111827; color: #E5E7EB; padding: 0.75rem; border-radius: 6px; overflow-x: auto; }
        .column-divider { border: none; border-top: 1px solid #E5E7EB; margin: 12px 0; }
        .column-alert { background: #FFF4E5; border-color: #F59E0B; }
        .column-latex { font-family: 'Courier New', monospace; }

        .column-quote:focus,
        .column-list:focus,
        .column-alert:focus,
        .column-latex:focus,
        .column-table:focus {
            border-color: #007CBA;
            box-shadow: 0 0 0 2px rgba(0, 124, 186, 0.1);
        }

        /* CODE BLOCK */
        .column-code-wrapper {
            margin: 0.5rem 0;
            border-radius: 6px;
            overflow: hidden;
        }

        .column-code-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 1rem;
            background: #1E293B;
            border-radius: 6px 6px 0 0;
        }

        .column-code-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .column-code-block {
            margin: 0;
            padding: 1rem;
            background: #0F172A;
            border-radius: 0 0 6px 6px;
            overflow-x: auto;
        }

        .column-code-block code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.875rem;
            line-height: 1.7;
            color: #E2E8F0;
            outline: none;
            white-space: pre;
            display: block;
            min-height: 3rem;
        }

        .column-code-block code:empty:before {
            content: attr(placeholder);
            color: #64748B;
        }

        /* ALERT BLOCK */
        .column-alert-wrapper {
            margin: 0.5rem 0;
        }

        .column-alert {
            display: flex;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            border-left: 4px solid;
        }

        .column-alert-info {
            background: #EFF6FF;
            border-left-color: #3B82F6;
            color: #1E40AF;
        }

        .column-alert-warning {
            background: #FFFBEB;
            border-left-color: #F59E0B;
            color: #92400E;
        }

        .column-alert-error {
            background: #FEF2F2;
            border-left-color: #EF4444;
            color: #991B1B;
        }

        .column-alert-success {
            background: #F0FDF4;
            border-left-color: #10B981;
            color: #065F46;
        }

        .column-alert-icon {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
        }

        .column-alert-icon svg {
            width: 20px;
            height: 20px;
        }

        .column-alert-content {
            flex: 1;
            outline: none;
            font-size: 0.9375rem;
            line-height: 1.5;
        }

        .column-alert-content:empty:before {
            content: attr(placeholder);
            color: rgba(0, 0, 0, 0.3);
            pointer-events: none;
        }

        /* LATEX BLOCK */
        .column-latex-wrapper {
            margin: 0.5rem 0;
            border-radius: 6px;
            overflow: hidden;
        }

        .column-latex-header {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            background: #F3E8FF;
            border-bottom: 1px solid #E9D5FF;
        }

        .column-latex-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #7C3AED;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .column-latex-content {
            padding: 1rem;
            background: white;
            font-family: 'Courier New', monospace;
            font-size: 0.9375rem;
            line-height: 1.6;
            outline: none;
            min-height: 2.2rem;
        }

        .column-latex-content:empty:before {
            content: attr(placeholder);
            color: #9CA3AF;
        }

        /* TABLE BLOCK */
        .column-table-wrapper {
            margin: 0.5rem 0;
            border-radius: 6px;
            overflow: hidden;
        }

        .column-table-container {
            overflow-x: auto;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
        }

        .column-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .column-table-cell {
            padding: 0.5rem 0.75rem;
            border: 1px solid #E5E7EB;
            font-size: 0.875rem;
            line-height: 1.5;
            color: #1E1E1E;
            outline: none;
            min-width: 80px;
        }

        .column-table-header {
            background: #F9FAFB;
            font-weight: 600;
            text-align: left;
        }

        .column-table-cell:empty:before {
            content: 'Digite...';
            color: #9CA3AF;
            pointer-events: none;
        }

        .column-table-cell:focus {
            background: #EBF8FF;
            border-color: #007CBA;
        }

        .column-media {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .column-media-input {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            font-size: 13px;
        }

        .column-media-preview img {
            max-width: 100%;
            border-radius: 6px;
        }

        .column-media-preview iframe {
            width: 100%;
            min-height: 180px;
            border-radius: 6px;
        }

        .column-image-wrapper {
            position: relative;
            margin: 0.5rem 0;
        }

        .column-image-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: #F8F9FA;
            border: 2px dashed #CBD5E0;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .column-image-placeholder:hover {
            background: #F1F3F5;
            border-color: #A0AEC0;
        }

        .column-image-placeholder svg {
            width: 32px;
            height: 32px;
            color: #9CA3AF;
            margin-bottom: 0.5rem;
        }

        .column-image-placeholder p {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 0.75rem;
        }

        .column-image-upload-btn {
            padding: 6px 12px;
            background: #007CBA;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: background 0.2s;
        }

        .column-image-upload-btn:hover {
            background: #005A87;
        }

        .column-image-container {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .column-image-container img {
            max-width: 100%;
            border-radius: 6px;
            display: block;
        }

        .column-image-remove {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 28px;
            height: 28px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .column-image-remove:hover {
            background: rgba(0, 0, 0, 0.9);
        }

        .column-video-wrapper {
            position: relative;
            margin: 0.5rem 0;
        }

        .column-video-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: #F8F9FA;
            border: 2px dashed #CBD5E0;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .column-video-placeholder:hover {
            background: #F1F3F5;
            border-color: #A0AEC0;
        }

        .column-video-placeholder svg {
            width: 32px;
            height: 32px;
            color: #9CA3AF;
            margin-bottom: 0.5rem;
        }

        .column-video-placeholder p {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 0.75rem;
        }

        .column-video-input {
            width: 100%;
            max-width: 300px;
            padding: 8px 10px;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            font-size: 13px;
        }

        .column-video-container {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%;
            background: #000;
            border-radius: 6px;
            overflow: hidden;
        }

        .column-video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        .column-video-remove {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 28px;
            height: 28px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
            z-index: 10;
        }

        .column-video-remove:hover {
            background: rgba(0, 0, 0, 0.9);
        }

        .column-add-more {
            margin-top: 0.75rem;
            color: #007CBA;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
        }

        .columns-controls {
            display: inline-flex;
            gap: 6px;
            padding: 4px 6px;
            background: white;
            border: 1px solid #E0E0E0;
            border-radius: 6px;
        }
        
        .columns-controls button {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            border: 1px solid #E0E0E0;
            border-radius: 4px;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 12px;
        }
        
        .columns-controls button:hover {
            background: #F0F7FA;
            border-color: #007CBA;
        }
        
        .columns-controls button.active {
            background: #007CBA;
            color: white;
            border-color: #007CBA;
        }
        
        .columns-controls button svg {
            width: 16px;
            height: 16px;
        }
        
        .columns-controls {
            margin-top: 0;
        }
        
        @media (max-width: 768px) {
            .columns-container {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</div>
