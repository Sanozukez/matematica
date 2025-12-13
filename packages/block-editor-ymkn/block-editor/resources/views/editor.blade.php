{{-- Block Editor Main Layout - Gutenberg Style --}}
<div 
    class="block-editor-container" 
    x-data="{ 
        blocks: [],
        isDragging: false 
    }"
>
    <style>
        /* Block Editor - Isolated Gutenberg-style Layout */
        .block-editor-container {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 65px); /* 65px = navbar height */
            background: #FFFFFF;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        }

        /* Top Toolbar - Sticky */
        .block-editor-toolbar {
            position: sticky;
            top: 65px; /* Below navbar */
            height: 65px;
            background: #FFFFFF;
            border-bottom: 1px solid #DCDCDE;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            z-index: 100;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .block-editor-toolbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .block-editor-logo-box {
            width: 65px;
            height: 65px;
            background: #000000;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0;
        }

        .block-editor-logo-box svg {
            width: 32px;
            height: 32px;
            color: #FFFFFF;
        }

        .block-editor-add-block-btn {
            width: 32px;
            height: 32px;
            background: #007CBA;
            border: none;
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .block-editor-add-block-btn:hover {
            background: #005a87;
        }

        .block-editor-add-block-btn svg {
            width: 12px;
            height: 12px;
            color: #FFFFFF;
        }

        .block-editor-save-btn {
            background: #007CBA;
            color: #FFFFFF;
            border: none;
            border-radius: 2px;
            padding: 0.5rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .block-editor-save-btn:hover {
            background: #005a87;
        }

        /* Main Content Area */
        .block-editor-main {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* Editor Canvas */
        .block-editor-canvas {
            flex: 1;
            overflow-y: auto;
            padding: 2rem;
            background: #F0F0F0;
        }

        .block-editor-canvas-inner {
            max-width: 840px;
            margin: 0 auto;
            background: #FFFFFF;
            border: 1px solid #DCDCDE;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            min-height: calc(100vh - 200px);
        }

        /* Right Sidebar - Fixed */
        .block-editor-sidebar {
            width: 280px;
            background: #FFFFFF;
            border-left: 1px solid #DCDCDE;
            overflow-y: auto;
            flex-shrink: 0;
        }

        .block-editor-sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid #DCDCDE;
            font-size: 0.875rem;
            font-weight: 600;
            color: #1E1E1E;
        }

        .block-editor-sidebar-content {
            padding: 1rem;
        }

        .block-editor-sidebar-section {
            margin-bottom: 1.5rem;
        }

        .block-editor-sidebar-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: #757575;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
            letter-spacing: 0.5px;
        }

        /* Blocks Container */
        .block-editor-blocks {
            padding: 2rem;
        }

        .block-editor-empty-state {
            padding: 4rem 2rem;
            text-align: center;
            color: #757575;
        }

        .block-editor-empty-state-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 1rem;
            color: #DCDCDE;
        }

        .block-editor-empty-state-text {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .block-editor-empty-state-hint {
            font-size: 0.875rem;
            color: #949494;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .block-editor-sidebar {
                position: fixed;
                right: -280px;
                top: 130px; /* navbar + toolbar */
                bottom: 0;
                z-index: 99;
                transition: right 0.3s;
            }

            .block-editor-sidebar.open {
                right: 0;
            }
        }
    </style>

    {{-- Top Toolbar --}}
    <div class="block-editor-toolbar">
        <div class="block-editor-toolbar-left">
            {{-- Logo Box with Open Book Icon --}}
            <div class="block-editor-logo-box">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>

            {{-- Add Block Button --}}
            <button class="block-editor-add-block-btn" title="Adicionar Bloco" @click="alert('Add block popup')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </button>
        </div>

        {{-- Save Button --}}
        <button class="block-editor-save-btn" @click="alert('Salvar conteúdo')">
            Salvar
        </button>
    </div>

    {{-- Main Content Area --}}
    <div class="block-editor-main">
        {{-- Editor Canvas --}}
        <div class="block-editor-canvas">
            <div class="block-editor-canvas-inner">
                <div class="block-editor-blocks">
                    {{-- Empty State --}}
                    <div class="block-editor-empty-state" x-show="blocks.length === 0">
                        <svg class="block-editor-empty-state-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <div class="block-editor-empty-state-text">
                            Começar a escrever ou clique no botão + para adicionar um bloco
                        </div>
                        <div class="block-editor-empty-state-hint">
                            Use blocos para adicionar texto, imagens, vídeos e mais
                        </div>
                    </div>

                    {{-- Blocks will be rendered here --}}
                    <template x-for="block in blocks" :key="block.id">
                        <div>Block placeholder</div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Right Sidebar --}}
        <aside class="block-editor-sidebar">
            <div class="block-editor-sidebar-header">
                Documento
            </div>
            <div class="block-editor-sidebar-content">
                <div class="block-editor-sidebar-section">
                    <div class="block-editor-sidebar-section-title">
                        Status
                    </div>
                    <p style="font-size: 0.875rem; color: #1E1E1E;">
                        Rascunho
                    </p>
                </div>

                <div class="block-editor-sidebar-section">
                    <div class="block-editor-sidebar-section-title">
                        Blocos
                    </div>
                    <p style="font-size: 0.875rem; color: #1E1E1E;">
                        <span x-text="blocks.length"></span> bloco(s)
                    </p>
                </div>
            </div>
        </aside>
    </div>
</div>
