{{-- Block Editor Main Layout - Gutenberg Style --}}

{{-- External CSS --}}
<link rel="stylesheet" href="{{ asset('vendor/block-editor/css/block-editor.css') }}">

{{-- External JavaScript (Block Types + Core Logic) --}}
<script type="module" src="{{ asset('vendor/block-editor/js/block-types.js') }}"></script>
<script type="module" src="{{ asset('vendor/block-editor/js/BlockEditorCore.js') }}"></script>

<div 
    class="block-editor-layout" 
    x-data="BlockEditorCore()"
    x-init="init()"
>
    {{-- Top Toolbar --}}
    <div class="block-editor-toolbar">
        <div class="block-editor-toolbar-left">
            <span class="block-editor-logo">Editor de Conteúdo</span>

            {{-- Add Block Button --}}
            <button class="block-editor-btn block-editor-btn-secondary" @click="openBlockInserter()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Adicionar Bloco
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

    {{-- Main Content Area --}}
    <div class="block-editor-main">
        {{-- Editor Canvas --}}
        <div class="block-editor-canvas" :class="{ 'shifted': canvasShifted }">
            <div class="block-editor-blocks" @click="handleCanvasClick($event)">
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

                {{-- Blocks Rendering --}}
                <template x-for="block in blocks" :key="block.id">
                    <div>
                        <template x-if="block.type === 'paragraph'">
                            @include('block-editor-ymkn::blocks.paragraph')
                        </template>
                        
                        <template x-if="block.type === 'heading'">
                            @include('block-editor-ymkn::blocks.heading')
                        </template>
                        
                        <template x-if="block.type === 'quote'">
                            @include('block-editor-ymkn::blocks.quote')
                        </template>
                        
                        <template x-if="block.type === 'code'">
                            @include('block-editor-ymkn::blocks.code')
                        </template>
                        
                        <template x-if="block.type === 'divider'">
                            @include('block-editor-ymkn::blocks.divider')
                        </template>
                        
                        {{-- TODO: Implementar image, video, list, alert, latex, table --}}
                    </div>
                </template>
            </div>
        </div>

        {{-- Right Sidebar (Document Info) --}}
        <aside class="block-editor-sidebar-right">
            <div class="sidebar-section">
                <div class="sidebar-section-title">Status</div>
                <div class="sidebar-info-item">Rascunho</div>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title">Blocos</div>
                <div class="sidebar-info-item">
                    <span x-text="blocks.length"></span> bloco(s)
                </div>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title">Último Salvamento</div>
                <div class="sidebar-info-item">--</div>
            </div>
        </aside>
    </div>
    
    {{-- Block Inserter Sidebar (Left) --}}
    @include('block-editor-ymkn::components.block-inserter')
</div>
