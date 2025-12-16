{{-- Block Editor Main Layout - Gutenberg Style --}}

{{-- Block Editor Core Logic (inline para evitar build process) --}}
<script>
    function BlockEditorCore() {
        return {
            // Estado
            blocks: [],
            focusedBlockId: null,
            lessonId: null,
            isSaving: false,
            showBlockInserter: false,
            blockSearchQuery: '',
            
            // Tipos de blocos disponíveis
            blockTypes: [
                { 
                    type: 'paragraph', 
                    label: 'Parágrafo', 
                    icon: '📝',
                    description: 'Texto simples com formatação'
                },
                { 
                    type: 'heading', 
                    label: 'Título', 
                    icon: '🔤',
                    description: 'Títulos H1 a H6'
                },
                { 
                    type: 'image', 
                    label: 'Imagem', 
                    icon: '🖼️',
                    description: 'Upload ou URL de imagem'
                },
                { 
                    type: 'video', 
                    label: 'Vídeo', 
                    icon: '🎥',
                    description: 'YouTube, Vimeo ou arquivo'
                },
                { 
                    type: 'code', 
                    label: 'Código', 
                    icon: '💻',
                    description: 'Bloco de código com syntax highlight'
                },
                { 
                    type: 'quote', 
                    label: 'Citação', 
                    icon: '💬',
                    description: 'Bloco de citação destacada'
                },
                { 
                    type: 'alert', 
                    label: 'Alerta', 
                    icon: '⚠️',
                    description: 'Aviso, info, sucesso ou erro'
                },
                { 
                    type: 'list', 
                    label: 'Lista', 
                    icon: '📋',
                    description: 'Lista ordenada ou não ordenada'
                },
                { 
                    type: 'latex', 
                    label: 'Fórmula LaTeX', 
                    icon: '∑',
                    description: 'Equações matemáticas'
                },
                { 
                    type: 'divider', 
                    label: 'Divisor', 
                    icon: '➖',
                    description: 'Linha separadora horizontal'
                },
                { 
                    type: 'table', 
                    label: 'Tabela', 
                    icon: '📊',
                    description: 'Tabela de dados'
                }
            ],
            filteredBlockTypes: [],
            
            /**
             * Inicialização do editor
             */
            init() {
                // Captura o ID da lesson da URL
                const urlParts = window.location.pathname.split('/');
                const lessonIndex = urlParts.indexOf('lessons');
                if (lessonIndex !== -1 && urlParts[lessonIndex + 1]) {
                    this.lessonId = urlParts[lessonIndex + 1];
                }
                
                // Inicializa lista filtrada com todos os blocos
                this.filteredBlockTypes = [...this.blockTypes];
                
                // Carrega blocos salvos (se existir)
                this.loadBlocks();
                
                console.log('Block Editor iniciado', { lessonId: this.lessonId });
            },
            
            /**
             * Filtra blocos na busca
             */
            filterBlocks() {
                const query = this.blockSearchQuery.toLowerCase();
                if (!query) {
                    this.filteredBlockTypes = [...this.blockTypes];
                    return;
                }
                
                this.filteredBlockTypes = this.blockTypes.filter(block => 
                    block.label.toLowerCase().includes(query) ||
                    block.description.toLowerCase().includes(query)
                );
            },
            
            /**
             * Abre popup de seleção de blocos
             */
            openBlockInserter() {
                this.showBlockInserter = true;
                this.blockSearchQuery = '';
                this.filteredBlockTypes = [...this.blockTypes];
            },
            
            /**
             * Insere bloco a partir do modal
             */
            insertBlockFromModal(type) {
                this.addBlock(type);
                this.showBlockInserter = false;
            },
            
            /**
             * Carrega blocos do servidor
             */
            async loadBlocks() {
                if (!this.lessonId) return;
                
                try {
                    const response = await fetch(`/api/lessons/${this.lessonId}/blocks`);
                    if (response.ok) {
                        const data = await response.json();
                        this.blocks = data.blocks || [];
                    }
                } catch (error) {
                    console.log('Nenhum bloco salvo ainda');
                }
            },
            
            /**
             * Adiciona novo bloco
             */
            addBlock(type = 'paragraph', afterIndex = null) {
                const newBlock = {
                    id: this.generateBlockId(),
                    type: type,
                    content: '',
                    order: this.blocks.length,
                    attributes: {}
                };
                
                if (afterIndex !== null) {
                    this.blocks.splice(afterIndex + 1, 0, newBlock);
                    this.reorderBlocks();
                } else {
                    this.blocks.push(newBlock);
                }
                
                // Foca no novo bloco após renderização
                this.$nextTick(() => {
                    this.focusBlock(newBlock.id);
                });
                
                return newBlock;
            },
            
            /**
             * Remove bloco
             */
            removeBlock(blockId) {
                const index = this.blocks.findIndex(b => b.id === blockId);
                if (index === -1) return;
                
                // Se é o único bloco, apenas limpa o conteúdo
                if (this.blocks.length === 1) {
                    this.blocks[0].content = '';
                    this.focusBlock(this.blocks[0].id);
                    return;
                }
                
                // Remove o bloco
                this.blocks.splice(index, 1);
                this.reorderBlocks();
                
                // Foca no bloco anterior ou próximo
                const newFocusIndex = index > 0 ? index - 1 : 0;
                if (this.blocks[newFocusIndex]) {
                    this.$nextTick(() => {
                        this.focusBlock(this.blocks[newFocusIndex].id);
                    });
                }
            },
            
            /**
             * Atualiza conteúdo de um bloco
             */
            updateBlockContent(blockId, content) {
                const block = this.blocks.find(b => b.id === blockId);
                if (block) {
                    block.content = content;
                }
            },
            
            /**
             * Foca em um bloco específico
             */
            focusBlock(blockId) {
                this.focusedBlockId = blockId;
                const element = document.querySelector(`[data-block-id="${blockId}"]`);
                if (element) {
                    const editable = element.querySelector('[contenteditable="true"]');
                    if (editable) {
                        editable.focus();
                        this.moveCursorToEnd(editable);
                    }
                }
            },
            
            /**
             * Move cursor para o final do elemento
             */
            moveCursorToEnd(element) {
                const range = document.createRange();
                const selection = window.getSelection();
                range.selectNodeContents(element);
                range.collapse(false);
                selection.removeAllRanges();
                selection.addRange(range);
            },
            
            /**
             * Handler de tecla Enter
             */
            handleEnter(event, blockId) {
                event.preventDefault();
                
                const block = this.blocks.find(b => b.id === blockId);
                if (!block) return;
                
                // Cria novo bloco
                const index = this.blocks.findIndex(b => b.id === blockId);
                this.addBlock(block.type, index);
            },
            
            /**
             * Handler de tecla Backspace
             */
            handleBackspace(event, blockId) {
                const element = event.target;
                const selection = window.getSelection();
                const isAtStart = selection.anchorOffset === 0;
                
                // Se cursor está no início e conteúdo vazio, remove bloco
                if (isAtStart && !element.textContent.trim()) {
                    event.preventDefault();
                    this.removeBlock(blockId);
                }
            },
            
            /**
             * Reordena blocos
             */
            reorderBlocks() {
                this.blocks.forEach((block, index) => {
                    block.order = index;
                });
            },
            
            /**
             * Gera ID único para bloco
             */
            generateBlockId() {
                return `block_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
            },
            
            /**
             * Serializa blocos para JSON
             */
            toJSON() {
                return {
                    lesson_id: this.lessonId,
                    blocks: this.blocks.map(block => ({
                        id: block.id,
                        type: block.type,
                        content: block.content,
                        order: block.order,
                        attributes: block.attributes
                    }))
                };
            },
            
            /**
             * Salva blocos no servidor
             */
            async save() {
                if (!this.lessonId) {
                    alert('ID da aula não encontrado');
                    return;
                }
                
                this.isSaving = true;
                
                try {
                    const response = await fetch(`/api/lessons/${this.lessonId}/blocks`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(this.toJSON())
                    });
                    
                    if (response.ok) {
                        console.log('Blocos salvos com sucesso');
                    } else {
                        throw new Error('Erro ao salvar');
                    }
                } catch (error) {
                    console.error('Erro ao salvar blocos:', error);
                    alert('Erro ao salvar conteúdo');
                } finally {
                    this.isSaving = false;
                }
            },
            
            /**
             * Cria bloco inicial quando clicar no empty state
             */
            startWriting() {
                if (this.blocks.length === 0) {
                    this.addBlock('paragraph');
                }
            }
        };
    }
</script>

<div 
    class="block-editor-container" 
    x-data="BlockEditorCore()"
    x-init="init()"
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
            <button class="block-editor-add-block-btn" title="Adicionar Bloco" @click="openBlockInserter()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </button>
        </div>

        {{-- Save Button --}}
        <button class="block-editor-save-btn" @click="save()" :disabled="isSaving">
            <span x-show="!isSaving">Salvar</span>
            <span x-show="isSaving">Salvando...</span>
        </button>
    </div>

    {{-- Main Content Area --}}
    <div class="block-editor-main">
        {{-- Editor Canvas --}}
        <div class="block-editor-canvas">
            <div class="block-editor-canvas-inner">
                <div class="block-editor-blocks">
                    {{-- Empty State --}}
                    <div 
                        class="block-editor-empty-state" 
                        x-show="blocks.length === 0"
                        @click="startWriting()"
                        style="cursor: pointer;"
                    >
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
                            
                            {{-- TODO: Adicionar mais tipos conforme necessário --}}
                        </div>
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
    
    {{-- Block Inserter Modal --}}
    @include('block-editor-ymkn::components.block-inserter')
</div>
