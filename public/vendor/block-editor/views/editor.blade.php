<div 
    class="block-editor-layout" 
    x-data="BlockEditorCore()"
    x-init="init()"
>
    {{-- Top Toolbar --}}
    <div class="block-editor-toolbar">
        <div class="block-editor-toolbar-left">
            {{-- Logo Box (65x65 preto com book-open) --}}
            <div class="block-editor-logo-box">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>

            {{-- Toggle Inserter Button (quadrado azul com + ou -) --}}
            <button 
                class="block-editor-add-block-btn" 
                @click="showBlockInserter = !showBlockInserter; canvasShifted = showBlockInserter"
                :title="showBlockInserter ? 'Fechar Blocos' : 'Adicionar Bloco'"
            >
                <svg x-show="!showBlockInserter" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <svg x-show="showBlockInserter" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                </svg>
            </button>

            {{-- Undo Button --}}
            <button 
                class="block-editor-history-btn" 
                @click="undo()"
                :disabled="!canUndo"
                title="Desfazer (Ctrl+Z)"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                </svg>
            </button>

            {{-- Redo Button --}}
            <button 
                class="block-editor-history-btn" 
                @click="redo()"
                :disabled="!canRedo"
                title="Refazer (Ctrl+Y)"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 15l6-6m0 0l-6-6m6 6H9a6 6 0 000 12h3" />
                </svg>
            </button>
        </div>

        <div class="block-editor-toolbar-right">
            {{-- Save Button --}}
            <button class="block-editor-btn block-editor-btn-primary" @click="save()" :disabled="isSaving">
                <template x-if="!isSaving">
                    <span>Salvar</span>
                </template>
                <template x-if="isSaving">
                    <span class="block-editor-loading">
                        <span class="block-editor-spinner"></span>
                        Salvando...
                    </span>
                </template>
            </button>
        </div>
    </div>

    {{-- Content + Sidebar Container --}}
    <div class="block-editor-main" @click.self="deselectBlock()">
        {{-- Editor Content (Título + Canvas unificados) --}}
        <div class="block-editor-content" :class="{ 'shifted': canvasShifted }" @click.self="deselectBlock()">
            <div class="block-editor-content-inner">
                {{-- Título da Aula (acima do canvas) --}}
                <div class="lesson-title-wrapper" @click.self="deselectBlock()">
                    <input 
                        type="text" 
                        class="lesson-title-input" 
                        placeholder="Adicionar título"
                        x-model="lessonTitle"
                    >
                </div>

                {{-- Editor Canvas --}}
                <div class="block-editor-canvas" @click.self="deselectBlock()">
            <div class="block-editor-blocks" @click="handleCanvasClick($event)" @click.self="deselectBlock()">
                {{-- Empty State --}}
                <div 
                    class="block-editor-empty" 
                    x-show="blocks.length === 0"
                    @click="startWriting()"
                    style="cursor: pointer;"
                >
                    <svg class="block-editor-empty-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <div class="block-editor-empty-title">
                        Comece a criar
                    </div>
                    <div class="block-editor-empty-text">
                        Clique aqui ou use o botão "+" para adicionar seu primeiro bloco
                    </div>
                </div>

                {{-- Blocks Rendering - cada bloco renderiza como 2 divs irmãs: bloco + inserter --}}
                <template x-for="(block, index) in blocks" :key="block.id">
                    <div style="display: contents;">
                        {{-- Bloco propriamente dito --}}
                        <template x-if="block.type === 'paragraph'">
                            @include('block-editor-ymnk::blocks.paragraph')
                        </template>
                        
                        <template x-if="block.type === 'heading'">
                            @include('block-editor-ymnk::blocks.heading')
                        </template>
                        
                        <template x-if="block.type === 'quote'">
                            @include('block-editor-ymnk::blocks.quote')
                        </template>
                        
                        <template x-if="block.type === 'code'">
                            @include('block-editor-ymnk::blocks.code')
                        </template>
                        
                        <template x-if="block.type === 'divider'">
                            @include('block-editor-ymnk::blocks.divider')
                        </template>
                        
                        <template x-if="block.type === 'image'">
                            @include('block-editor-ymnk::blocks.image')
                        </template>
                        
                        <template x-if="block.type === 'video'">
                            @include('block-editor-ymnk::blocks.video')
                        </template>
                        
                        <template x-if="block.type === 'list'">
                            @include('block-editor-ymnk::blocks.list')
                        </template>
                        
                        <template x-if="block.type === 'alert'">
                            @include('block-editor-ymnk::blocks.alert')
                        </template>
                        
                        <template x-if="block.type === 'latex'">
                            @include('block-editor-ymnk::blocks.latex')
                        </template>
                        
                        <template x-if="block.type === 'table'">
                            @include('block-editor-ymnk::blocks.table')
                        </template>
                        
                        <template x-if="block.type === 'columns'">
                            @include('block-editor-ymnk::blocks.columns')
                        </template>
                        
                        {{-- Inserter entre blocos (div irmã, mesmo nível) --}}
                        @include('block-editor-ymnk::components.block-inserter-between')
                    </div>
                </template>
            </div>
            </div>
            </div>
        </div>

        {{-- Right Sidebar (Document Info) --}}
        <aside class="block-editor-sidebar-right" @click="deselectBlock()">
            <!-- Status Section -->
            <div class="sidebar-section">
                <div class="sidebar-section-title">📊 Status</div>
                <div class="sidebar-info-item">
                    <span class="sidebar-label">Estado:</span>
                    <span x-text="lesson?.is_active ? '✓ Ativa' : '⊘ Inativa'" 
                          :class="lesson?.is_active ? 'sidebar-active' : 'sidebar-inactive'"
                          class="font-semibold"></span>
                </div>
                <div class="sidebar-info-item">
                    <span class="sidebar-label">Tipo:</span>
                    <span x-text="lesson?.type" class="sidebar-type-badge"></span>
                </div>
            </div>
            
            <!-- Content Stats Section -->
            <div class="sidebar-section">
                <div class="sidebar-section-title">📝 Conteúdo</div>
                <div class="sidebar-info-item">
                    <span class="sidebar-label">Blocos:</span>
                    <span x-text="lesson?.stats?.blocks_count || blocks.length"></span>
                </div>
                <div class="sidebar-info-item">
                    <span class="sidebar-label">Palavras:</span>
                    <span x-text="lesson?.stats?.words_count || '~'"></span>
                </div>
                <div class="sidebar-info-item">
                    <span class="sidebar-label">Duração:</span>
                    <span x-text="(lesson?.duration_minutes || 0) + ' min'"></span>
                </div>
            </div>
            
            <!-- Save History Section -->
            <div class="sidebar-section">
                <div class="sidebar-section-title">💾 Salvamento</div>
                <div class="sidebar-info-item">
                    <span class="sidebar-label">Último:</span>
                    <span x-text="lesson?.updated_at || '--'" class="sidebar-date"></span>
                </div>
                <div class="sidebar-info-item">
                    <span class="sidebar-label">Criado:</span>
                    <span x-text="lesson?.created_at || '--'" class="sidebar-date"></span>
                </div>
            </div>
            
            <!-- Keyboard Shortcuts Section -->
            <div class="sidebar-section">
                <div class="sidebar-section-title">⌨️ Atalhos</div>
                <div class="sidebar-shortcut-list">
                    <div class="sidebar-shortcut-item">
                        <span class="sidebar-key">Ctrl+Z</span>
                        <span class="sidebar-key-desc">Desfazer</span>
                    </div>
                    <div class="sidebar-shortcut-item">
                        <span class="sidebar-key">Ctrl+Y</span>
                        <span class="sidebar-key-desc">Refazer</span>
                    </div>
                    <div class="sidebar-shortcut-item">
                        <span class="sidebar-key">Delete</span>
                        <span class="sidebar-key-desc">Excluir bloco</span>
                    </div>
                    <div class="sidebar-shortcut-item">
                        <span class="sidebar-key">/</span>
                        <span class="sidebar-key-desc">Slash commands</span>
                    </div>
                </div>
            </div>
        </aside>
    </div>
    
    {{-- Block Inserter Sidebar (Left) --}}
    @include('block-editor-ymnk::components.block-inserter')
    
    {{-- Toaster de Salvamento --}}
    <div 
        class="save-toast"
        x-show="showSaveToast"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
    >
        <span x-text="saveToastMessage"></span>
    </div>
    
    {{-- Slash Commands Menu --}}
    <div 
        x-show="showSlashMenu"
        x-transition
        @click.outside="hideSlashCommandMenu()"
        class="slash-commands-menu"
        :style="`top: ${slashMenuPosition.y}px; left: ${slashMenuPosition.x}px;`"
    >
        <div class="slash-menu-list">
            <template x-for="(blockType, idx) in slashFilteredBlocks" :key="blockType.type">
                <button 
                    @click="executeSlashCommand(blockType.type)"
                    class="slash-menu-item"
                    :class="{ 'is-selected': idx === slashSelectedIndex }"
                >
                    <div class="slash-menu-item-icon" x-html="blockType.icon"></div>
                    <div class="slash-menu-item-content">
                        <div class="slash-menu-item-label" x-text="blockType.label"></div>
                    </div>
                </button>
            </template>
            <div x-show="slashFilteredBlocks.length === 0" class="slash-menu-empty">
                Nenhum bloco encontrado
            </div>
        </div>
    </div>
    
    {{-- Block Transform Menu (pelo ícone) --}}
    <div 
        x-show="showBlockTypeMenu"
        x-transition
        @click.outside="hideBlockTransformMenu()"
        class="block-transform-menu"
        :style="`top: ${blockTypeMenuPosition.y}px; left: ${blockTypeMenuPosition.x}px;`"
    >
        <div class="transform-menu-header">Transformar em:</div>
        <template x-for="option in getTransformOptions($data.blocks.find(b => b.id === transformTargetBlockId)?.type || 'paragraph')" :key="option.type">
            <button 
                @click="transformBlock(option.type)"
                class="transform-menu-item"
            >
                <span x-text="option.icon"></span>
                <span x-text="option.label"></span>
            </button>
        </template>
    </div>
</div>
