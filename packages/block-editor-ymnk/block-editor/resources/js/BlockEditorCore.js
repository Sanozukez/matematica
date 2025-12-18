/**
 * Block Editor Core - Gutenberg-style Block Management
 * 
 * ✨ REFATORADO - Agora usa módulos especializados:
 * - BlockManager: CRUD de blocos
 * - EventHandlers: Teclado, mouse, navegação
 * - DragDropManager: Arrastar e soltar
 * - FormatManager: Formatação de texto
 * - BlockRenderers: Renderizadores específicos (image, video, latex)
 * - StateManager: Save/Load, persistência
 * 
 * Princípio SRP: Core apenas orquestra os módulos
 * 
 * Exposto globalmente como window.BlockEditorCore
 * 
 * De 779 linhas → 350 linhas (55% redução!)
 */

window.BlockEditorCore = function() {
    return {
        // ========== ESTADO ==========
        blocks: [],
        focusedBlockId: null,
        lessonId: null,
        lessonTitle: '',
        lesson: null, // Dados completos da lesson
        lastSavedAt: null, // Data/hora do último salvamento
        isSaving: false,
        hasChanges: false,
        showSaveToast: false,
        saveToastMessage: '',
        showBlockInserter: false,
        blockSearchQuery: '',
        canvasShifted: false,
        focusedColumnIndex: null,
        
        // Tipos de blocos disponíveis
        blockTypes: window.BLOCK_TYPES,
        filteredBlockTypes: [],
        
        // Slash Commands
        showSlashMenu: false,
        slashMenuPosition: { x: 0, y: 0 },
        slashCommand: '',
        slashFilteredBlocks: [],
        slashMenuFocusedBlockId: null,
        slashSelectedIndex: 0,

        // Colunas
        focusedColumnIndex: null,
        focusedInnerBlockId: null,
        
        // Block Transform
        showBlockTypeMenu: false,
        blockTypeMenuPosition: { x: 0, y: 0 },
        transformTargetBlockId: null,
        
        // Debouncer para auto-save
        _autoSaveDebouncer: null,
        
        // History (Undo/Redo)
        canUndo: false,
        canRedo: false,
        
        // ========== INICIALIZAÇÃO ==========
        
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
            
            // Cria debouncer
            this._autoSaveDebouncer = window.StateManager.createDebouncer();
            
            // Carrega blocos salvos (se existir)
            this.loadBlocks();
            
            // Inicializa history com o estado inicial
            this.$nextTick(() => {
                window.HistoryManager.save(this.blocks);
                this.updateHistoryState();
            });
            
            // Listeners de teclado para Undo/Redo
            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 'z' && !e.shiftKey) {
                    e.preventDefault();
                    this.undo();
                } else if ((e.ctrlKey || e.metaKey) && (e.key === 'y' || (e.key === 'z' && e.shiftKey))) {
                    e.preventDefault();
                    this.redo();
                } else if ((e.key === 'Delete' || e.key === 'Backspace') && this.focusedBlockId) {
                    // Se há bloco focado e não está editando texto, remove o bloco
                    const target = e.target;
                    const isEditing = target.isContentEditable || target.tagName === 'INPUT' || target.tagName === 'TEXTAREA';
                    if (!isEditing) {
                        e.preventDefault();
                        this.removeBlock(this.focusedBlockId);
                    }
                }
            });
            
            console.log('✅ Block Editor iniciado (versão modular)', { lessonId: this.lessonId });
        },
        
        // ========== BLOCK MANAGEMENT (delegado para BlockManager) ==========
        
        /**
         * Adiciona novo bloco
         */
        addBlock(type = 'paragraph', afterIndex = null) {
            const newBlock = window.BlockManager.addBlock(this.blocks, type, afterIndex);
            
            // Salva no histórico
            this.saveToHistory();
            
            // Foca no novo bloco após renderização
            this.$nextTick(() => {
                this.focusBlock(newBlock.id);
            });
            
            return newBlock;
        },

        /**
         * Normaliza colunas (garante array e quantidade conforme columnCount)
         */
        getColumns(block) {
            const count = block.attributes?.columnCount || 2;
            if (!Array.isArray(block.content)) {
                block.content = [];
            }
            for (let i = 0; i < count; i++) {
                if (!block.content[i]) {
                    block.content[i] = { blocks: [] };
                }
                if (!Array.isArray(block.content[i].blocks)) {
                    block.content[i].blocks = [];
                }
            }
            // Se houver colunas excedentes, mantém (não corta) para não perder dados
            return block.content.slice(0, Math.max(block.content.length, count));
        },
        
        /**
         * Remove bloco
         */
        removeBlock(blockId) {
            const result = window.BlockManager.removeBlock(this.blocks, blockId);
            
            // Salva no histórico
            this.saveToHistory();
            
            if (result && result.focusBlockId) {
                this.$nextTick(() => {
                    this.focusBlock(result.focusBlockId);
                });
            }
        },
        
        /**
         * Atualiza conteúdo de um bloco
         */
        updateBlockContent(blockId, content) {
            window.BlockManager.updateBlockContent(this.blocks, blockId, content);
        },
        
        /**
         * Atualiza atributos de um bloco
         */
        updateBlockAttributes(blockId, attributes) {
            window.BlockManager.updateBlockAttributes(this.blocks, blockId, attributes);
        },
        
        /**
         * Move bloco para cima
         */
        moveBlockUp(blockId) {
            window.BlockManager.moveBlockUp(this.blocks, blockId);
        },
        
        /**
         * Move bloco para baixo
         */
        moveBlockDown(blockId) {
            window.BlockManager.moveBlockDown(this.blocks, blockId);
        },
        
        /**
         * Duplica um bloco
         */
        duplicateBlock(blockId) {
            window.BlockManager.duplicateBlock(this.blocks, blockId);
        },
        
        // ========== EVENT HANDLERS (delegado para EventHandlers) ==========
        
        /**
         * Handler de tecla Enter
         */
        handleEnter(event, blockId) {
            // Se o menu de slash estiver aberto, Enter confirma o item selecionado
            if (this.showSlashMenu && this.slashMenuFocusedBlockId === blockId && this.slashFilteredBlocks.length > 0) {
                event.preventDefault();
                const selected = this.slashFilteredBlocks[this.slashSelectedIndex] || this.slashFilteredBlocks[0];
                if (selected) {
                    this.executeSlashCommand(selected.type);
                }
                return;
            }

            window.EventHandlers.handleEnter(
                event, 
                this.blocks, 
                blockId, 
                (type, index) => this.addBlock(type, index)
            );
        },
        
        /**
         * Handler de tecla Backspace
         */
        handleBackspace(event, blockId) {
            window.EventHandlers.handleBackspace(
                event, 
                blockId, 
                (id) => this.removeBlock(id)
            );
        },
        
        /**
         * Handler de clique no canvas
         */
        handleCanvasClick(event) {
            window.EventHandlers.handleCanvasClick(
                event,
                this.blocks,
                (type) => this.addBlock(type),
                (id) => this.focusBlock(id)
            );
        },
        
        /**
         * Foca em um bloco específico
         */
        focusBlock(blockId) {
            this.focusedBlockId = blockId;
            window.EventHandlers.focusBlock(blockId);
        },

        focusInnerBlock(columnsBlockId, columnIndex, innerBlockId) {
            this.focusedBlockId = columnsBlockId;
            this.focusedColumnIndex = columnIndex;
            this.focusedInnerBlockId = innerBlockId;
        },
        
        /**
         * Desseleciona bloco ativo
         */
        deselectBlock() {
            this.focusedBlockId = null;
        },
        
        /**
         * Cria bloco inicial quando clicar no empty state
         */
        startWriting() {
            if (this.blocks.length === 0) {
                this.addBlock('paragraph');
            }
        },
        
        // ========== LIST BLOCK HANDLERS ==========
        
        updateListItem(blockId, index, value) {
            window.EventHandlers.updateListItem(blockId, index, value, this.blocks);
            this.debouncedSave();
        },
        
        addListItem(blockId, afterIndex) {
            const success = window.EventHandlers.addListItem(blockId, afterIndex, this.blocks);
            
            if (success) {
                // Foca no novo item após renderização
                this.$nextTick(() => {
                    const listItems = document.querySelectorAll(`[data-block-id="${blockId}"] .block-list-item`);
                    if (listItems[afterIndex + 1]) {
                        listItems[afterIndex + 1].focus();
                    }
                });
            }
        },
        
        handleListBackspace(event, blockId, index) {
            const result = window.EventHandlers.handleListBackspace(
                event,
                blockId,
                index,
                this.blocks,
                (id) => this.removeBlock(id)
            );
            
            if (result && result.action === 'remove-item') {
                // Foca no item anterior
                this.$nextTick(() => {
                    const listItems = document.querySelectorAll(`[data-block-id="${blockId}"] .block-list-item`);
                    if (listItems[result.focusIndex]) {
                        listItems[result.focusIndex].focus();
                    }
                });
            }
        },
        
        // ========== TABLE BLOCK HANDLERS ==========
        
        updateTableCell(blockId, rowIndex, colIndex, value) {
            window.EventHandlers.updateTableCell(blockId, rowIndex, colIndex, value, this.blocks);
            this.debouncedSave();
        },
        
        // ========== FORMATTING (delegado para FormatManager) ==========
        
        /**
         * Aplica formatação (bold, italic, underline, etc)
         */
        applyFormatting(blockId, format) {
            window.FormatManager.applyFormatting(blockId, format);
        },
        
        /**
         * Aplica cor de texto ao bloco
         */
        applyTextColor(blockId, colorClass) {
            window.FormatManager.applyTextColor(blockId, colorClass, this.blocks);
        },
        
        /**
         * Aplica alinhamento ao bloco
         */
        applyAlignment(blockId, alignment) {
            window.FormatManager.applyAlignment(blockId, alignment, this.blocks);
        },
        
        /**
         * Insere link no texto selecionado
         */
        insertLink(blockId, url) {
            window.FormatManager.insertLink(blockId, url);
        },
        
        // ========== BLOCK RENDERERS (delegado para BlockRenderers) ==========
        
        /**
         * IMAGE BLOCK: Handle upload de imagem
         */
        handleImageUpload(event, blockId) {
            window.BlockRenderers.handleImageUpload(
                event,
                blockId,
                (id, content) => this.updateBlockContent(id, content),
                () => this.debouncedSave()
            );
        },
        
        /**
         * VIDEO BLOCK: Gera embed do YouTube/Vimeo
         */
        getVideoEmbed(url) {
            return window.BlockRenderers.getVideoEmbed(url);
        },
        
        /**
         * LATEX BLOCK: Renderiza LaTeX
         */
        renderLatex(latex, mode = 'inline') {
            return window.BlockRenderers.renderLatex(latex, mode);
        },
        
        // ========== DRAG & DROP (delegado para DragDropManager) ==========
        
        handleDragStart(event, blockId) {
            window.DragDropManager.handleDragStart(event, blockId);
        },
        
        handleDragOver(event, blockId) {
            window.DragDropManager.handleDragOver(event, blockId);
        },
        
        handleDragLeave(event) {
            window.DragDropManager.handleDragLeave(event);
        },
        
        handleDrop(event, targetBlockId) {
            const success = window.DragDropManager.handleDrop(event, targetBlockId, this.blocks);
            if (success) {
                this.debouncedSave();
            }
        },
        
        handleDragEnd(event) {
            window.DragDropManager.handleDragEnd(event);
        },
        
        // ========== STATE MANAGEMENT (delegado para StateManager) ==========
        
        /**
         * Carrega blocos do servidor
         */
        async loadBlocks() {
            const data = await window.StateManager.loadBlocks(this.lessonId);
            
            if (data) {
                this.blocks = data.blocks;
                this.lessonTitle = data.lessonTitle;
                this.lesson = data.lesson; // Guarda dados completos
                this.hasChanges = false;
            }
        },
        
        /**
         * Salva blocos no servidor
         */
        async saveBlocks() {
            if (this.isSaving) return;
            
            this.isSaving = true;
            
            // Atualiza timestamp de salvamento
            const now = new Date();
            this.lastSavedAt = now.toLocaleTimeString('pt-BR', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            
            const result = await window.StateManager.saveBlocks(
                this.lessonId,
                this.blocks,
                this.lessonTitle
            );
            
            this.isSaving = false;
            
            if (result.success) {
                this.hasChanges = false;
                this.showToast('Salvo com sucesso!', 'success');
            } else {
                this.showToast('Erro ao salvar', 'error');
            }
        },
        
        /**
         * Debounce para auto-save (3 minutos após parar de editar)
         */
        debouncedSave() {
            this.hasChanges = true;
            
            if (this._autoSaveDebouncer) {
                this._autoSaveDebouncer.debounce(() => {
                    if (this.hasChanges) {
                        this.saveBlocks();
                    }
                }, 180000); // 3 minutos
            }
        },
        
        /**
         * Salva blocos imediatamente (botão Save)
         */
        async save() {
            await this.saveBlocks();
        },
        
        /**
         * Serializa blocos para JSON
         */
        toJSON() {
            return {
                blocks: window.BlockManager.serializeBlocks(this.blocks)
            };
        },
        
        // ========== BLOCK INSERTER ==========
        
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
         * Insere bloco a partir do sidebar (NÃO fecha sidebar)
         * SE bloco focado é parágrafo vazio, SUBSTITUI ao invés de adicionar
         */
        insertBlockFromModal(type) {
            const focusedBlock = this.blocks.find(b => b.id === this.focusedBlockId);

            // Se estamos dentro de uma coluna, insere na coluna focada
            if (focusedBlock && focusedBlock.type === 'columns' && this.focusedColumnIndex !== null) {
                const column = focusedBlock.content?.[this.focusedColumnIndex];
                const currentIdx = column?.blocks?.findIndex(b => b.id === this.focusedInnerBlockId) ?? -1;
                this.addInnerBlock(focusedBlock.id, this.focusedColumnIndex, type, currentIdx);
                return;
            }

            // Se bloco focado é parágrafo vazio, substitui
            if (focusedBlock && 
                focusedBlock.type === 'paragraph' && 
                (!focusedBlock.content || focusedBlock.content.trim() === '')) {
                
                focusedBlock.type = type;
                focusedBlock.content = '';
                focusedBlock.attributes = type === 'heading' ? { level: 2 } : {};
                
                // Re-foca o bloco após mudança de tipo
                this.$nextTick(() => {
                    this.focusBlock(focusedBlock.id);
                });
            } else {
                // Caso contrário, adiciona novo bloco
                this.addBlock(type);
            }
            
            // Sidebar permanece aberta (comportamento WordPress)
        },
        
        // ========== SLASH COMMANDS ==========
        
        /**
         * Handle input em contenteditable - detecta comandos /
         */
        handleContentInput(event, blockId) {
            const element = event.target;
            const text = element.textContent || '';
            
            // Atualiza conteúdo do bloco
            this.updateBlockContent(blockId, element.innerHTML);
            
            // Verifica se deve mostrar menu de comandos slash
            if (window.SlashCommands && window.SlashCommands.shouldShowMenu(text)) {
                const command = window.SlashCommands.extractCommand(text);
                this.showSlashCommandMenu(blockId, element, command);
            } else {
                this.hideSlashCommandMenu();
            }
            
            this.debouncedSave();
        },
        
        /**
         * Mostra menu de comandos slash
         */
        showSlashCommandMenu(blockId, element, command) {
            this.slashCommand = command || '';
            this.slashMenuFocusedBlockId = blockId;
            
            // Filtra blocos por comando
            this.slashFilteredBlocks = window.SlashCommands.filterBlocksByCommand(
                this.blockTypes,
                this.slashCommand
            );
            // Remove parágrafo do menu slash
            this.slashFilteredBlocks = this.slashFilteredBlocks.filter(b => b.type !== 'paragraph');
            // Seleciona sempre o primeiro item por padrão
            this.slashSelectedIndex = 0;
            
            // Posiciona menu próximo ao cursor
            const position = window.SlashCommands.getCursorPosition(element);
            if (position) {
                this.slashMenuPosition = position.absolute;
                this.showSlashMenu = true;
            }
        },

        /**
         * Navega no menu slash com setas
         */
        navigateSlashMenu(direction, blockId) {
            if (!this.showSlashMenu || this.slashMenuFocusedBlockId !== blockId) return;
            const total = this.slashFilteredBlocks.length;
            if (total === 0) return;
            if (direction === 'up') {
                this.slashSelectedIndex = (this.slashSelectedIndex - 1 + total) % total;
            } else if (direction === 'down') {
                this.slashSelectedIndex = (this.slashSelectedIndex + 1) % total;
            }
        },
        
        /**
         * Esconde menu de comandos slash
         */
        hideSlashCommandMenu() {
            this.showSlashMenu = false;
            this.slashCommand = '';
            this.slashFilteredBlocks = [];
            this.slashMenuFocusedBlockId = null;
            this.slashSelectedIndex = 0;
        },
        
        /**
         * Executa comando slash - transforma ou cria bloco
         */
        executeSlashCommand(blockType) {
            if (!this.slashMenuFocusedBlockId) return;
            
            const block = this.blocks.find(b => b.id === this.slashMenuFocusedBlockId);
            if (!block) return;
            
            const element = document.querySelector(`[data-block-id="${this.slashMenuFocusedBlockId}"]`);
            const editable = element?.querySelector('[contenteditable="true"]');
            
            if (editable) {
                const text = editable.textContent || '';
                const isOnlyCommand = window.SlashCommands.isBlockOnlyCommand(text);
                
                if (isOnlyCommand) {
                    // Se bloco só tem comando, transforma o bloco
                    block.type = blockType;
                    block.content = '';
                    if (blockType === 'heading') {
                        block.attributes = { level: 2 };
                    }
                } else {
                    // Se tem texto além do comando, remove comando e transforma
                    const cleanText = window.SlashCommands.removeCommandFromText(text);
                    editable.textContent = cleanText;
                    
                    block.type = blockType;
                    block.content = cleanText;
                    if (blockType === 'heading') {
                        block.attributes = { level: 2 };
                    }
                }
                
                // Re-foca o bloco
                this.$nextTick(() => {
                    this.focusBlock(block.id);
                });
            }
            
            this.hideSlashCommandMenu();
        },
        
        // ========== BLOCK TRANSFORM ==========
        
        /**
         * Mostra menu de transformação de bloco (pelo ícone)
         */
        showBlockTransformMenu(blockId, event) {
            this.transformTargetBlockId = blockId;
            
            // Posiciona menu abaixo do ícone
            const rect = event.target.getBoundingClientRect();
            this.blockTypeMenuPosition = {
                x: rect.left,
                y: rect.bottom + 5
            };
            
            this.showBlockTypeMenu = true;
        },
        
        /**
         * Esconde menu de transformação
         */
        hideBlockTransformMenu() {
            this.showBlockTypeMenu = false;
            this.transformTargetBlockId = null;
        },
        
        /**
         * Transforma bloco para novo tipo
         */
        transformBlock(toType) {
            if (!this.transformTargetBlockId) return;
            
            const block = this.blocks.find(b => b.id === this.transformTargetBlockId);
            if (!block) return;
            
            const element = document.querySelector(`[data-block-id="${this.transformTargetBlockId}"]`);
            
            // Tenta transformar
            const result = window.BlockTransform.transform(block, toType, element);
            
            if (result.success) {
                // Re-renderiza o bloco
                this.$nextTick(() => {
                    this.focusBlock(block.id);
                });
            } else if (result.action === 'replace') {
                // Se não pode transformar, substitui
                const index = this.blocks.findIndex(b => b.id === this.transformTargetBlockId);
                if (index !== -1) {
                    this.blocks.splice(index, 1);
                    this.addBlock(toType, index - 1);
                }
            }
            
            this.hideBlockTransformMenu();
            this.debouncedSave();
        },
        
        /**
         * Obtém opções de transformação para um bloco
         */
        getTransformOptions(blockType) {
            return window.BlockTransform?.getTransformOptions(blockType) || [];
        },
        
        // ========== COLUNAS ==========
        
        /**
         * Define o número de colunas de um bloco columns
         */
        setColumnCount(blockId, count) {
            const block = this.blocks.find(b => b.id === blockId);
            if (!block || block.type !== 'columns') return;
            
            if (!block.attributes) block.attributes = {};
            const oldCount = block.attributes.columnCount || 2;
            block.attributes.columnCount = count;
            
            // Ajusta o array de colunas
            if (!Array.isArray(block.content)) {
                block.content = [];
            }
            
            // Adiciona ou remove colunas
            if (count > oldCount) {
                // Adiciona colunas vazias
                for (let i = oldCount; i < count; i++) {
                    block.content.push({ blocks: [] });
                }
            } else if (count < oldCount) {
                // Remove colunas extras (move blocos para última coluna)
                const removedColumns = block.content.splice(count);
                removedColumns.forEach(col => {
                    if (col.blocks && col.blocks.length > 0) {
                        block.content[count - 1].blocks.push(...col.blocks);
                    }
                });
            }
            
            this.debouncedSave();
        },
        
        /**
         * Adiciona um bloco a uma coluna específica
         */
        addBlockToColumn(columnsBlockId, columnIndex) {
            const columnsBlock = this.blocks.find(b => b.id === columnsBlockId);
            if (!columnsBlock || columnsBlock.type !== 'columns') return;
            
            if (!Array.isArray(columnsBlock.content)) {
                columnsBlock.content = [];
            }
            
            if (!columnsBlock.content[columnIndex]) {
                columnsBlock.content[columnIndex] = { blocks: [] };
            }
            
            if (!columnsBlock.content[columnIndex].blocks) {
                columnsBlock.content[columnIndex].blocks = [];
            }
            
            const newBlock = this.createInnerBlock('paragraph');
            columnsBlock.content[columnIndex].blocks.push(newBlock);
            this.debouncedSave();
        },

        /**
         * Adiciona bloco interno com tipo específico
         */
        addInnerBlock(columnsBlockId, columnIndex, type = 'paragraph', afterIndex = null) {
            const columnsBlock = this.blocks.find(b => b.id === columnsBlockId);
            if (!columnsBlock || columnsBlock.type !== 'columns') return;
            if (!Array.isArray(columnsBlock.content)) columnsBlock.content = [];
            if (!columnsBlock.content[columnIndex]) columnsBlock.content[columnIndex] = { blocks: [] };
            if (!Array.isArray(columnsBlock.content[columnIndex].blocks)) columnsBlock.content[columnIndex].blocks = [];
            const newBlock = this.createInnerBlock(type);
            const list = columnsBlock.content[columnIndex].blocks;
            if (afterIndex !== null && afterIndex >= 0 && afterIndex < list.length) {
                list.splice(afterIndex + 1, 0, newBlock);
            } else {
                list.push(newBlock);
            }
            this.$nextTick(() => {
                const el = document.querySelector(`[data-inner-block-id="${newBlock.id}"]`);
                el?.focus();
            });
            this.debouncedSave();
        },

        createInnerBlock(type = 'paragraph') {
            const base = {
                id: Date.now() + '-' + Math.random().toString(36).substr(2, 9),
                type,
                content: '',
                attributes: {}
            };
            if (type === 'heading') {
                base.attributes = { level: 3 };
            }
            if (type === 'list') {
                base.content = [''];
            }
            return base;
        },

        /**
         * Atualiza conteúdo de um bloco interno (colunas)
         */
        updateInnerBlockContent(columnsBlockId, columnIndex, innerBlockId, content) {
            const columnsBlock = this.blocks.find(b => b.id === columnsBlockId);
            const column = columnsBlock?.content?.[columnIndex];
            if (!column || !Array.isArray(column.blocks)) return;
            const innerBlock = column.blocks.find(b => b.id === innerBlockId);
            if (innerBlock) {
                innerBlock.content = content;
                this.debouncedSave();
            }
        },

        /**
         * Enter dentro da coluna: cria novo parágrafo abaixo
         */
        handleInnerEnter(event, columnsBlockId, columnIndex, innerBlockId) {
            event.preventDefault();
            const columnsBlock = this.blocks.find(b => b.id === columnsBlockId);
            const column = columnsBlock?.content?.[columnIndex];
            if (!column || !Array.isArray(column.blocks)) return;
            const idx = column.blocks.findIndex(b => b.id === innerBlockId);
            this.addInnerBlock(columnsBlockId, columnIndex, 'paragraph', idx);
        },

        /**
         * Backspace em bloco interno: remove se vazio
         */
        handleInnerBackspace(event, columnsBlockId, columnIndex, innerBlockId) {
            const selectionAtStart = window.getSelection()?.anchorOffset === 0;
            const target = event.target;
            const isEmpty = !target.textContent.trim();
            if (!selectionAtStart || !isEmpty) return;
            event.preventDefault();
            const columnsBlock = this.blocks.find(b => b.id === columnsBlockId);
            const column = columnsBlock?.content?.[columnIndex];
            if (!column || !Array.isArray(column.blocks)) return;
            const idx = column.blocks.findIndex(b => b.id === innerBlockId);
            if (idx === -1) return;
            if (column.blocks.length === 1) {
                // Mantém um bloco vazio para não quebrar a coluna
                column.blocks[0].content = '';
            } else {
                column.blocks.splice(idx, 1);
                const focusId = column.blocks[Math.max(0, idx - 1)]?.id;
                this.$nextTick(() => {
                    const el = document.querySelector(`[data-inner-block-id="${focusId}"]`);
                    el?.focus();
                });
            }
            this.debouncedSave();
        },

        /**
         * Atualiza atributos do bloco interno (por exemplo, heading level)
         */
        updateInnerBlockAttributes(columnsBlockId, columnIndex, innerBlockId, attributes) {
            const columnsBlock = this.blocks.find(b => b.id === columnsBlockId);
            const column = columnsBlock?.content?.[columnIndex];
            if (!column || !Array.isArray(column.blocks)) return;
            const innerBlock = column.blocks.find(b => b.id === innerBlockId);
            if (innerBlock) {
                innerBlock.attributes = { ...(innerBlock.attributes || {}), ...attributes };
                this.debouncedSave();
            }
        },
        
        // ========== UI FEEDBACK ==========
        
        /**
         * Exibe toaster de feedback
         */
        showToast(message, type = 'success') {
            console.log('🔔 showToast chamado:', message);
            this.saveToastMessage = message;
            this.showSaveToast = true;
            
            setTimeout(() => {
                this.showSaveToast = false;
            }, 3000);
        },

        // ========== UNDO/REDO ==========
        
        /**
         * Faz undo
         */
        undo() {
            const previousState = window.HistoryManager.undo();
            if (previousState) {
                this.blocks = previousState;
                this.updateHistoryState();
                this.focusedBlockId = null;
            }
        },

        /**
         * Faz redo
         */
        redo() {
            const nextState = window.HistoryManager.redo();
            if (nextState) {
                this.blocks = nextState;
                this.updateHistoryState();
                this.focusedBlockId = null;
            }
        },

        /**
         * Atualiza estado dos botões de undo/redo
         */
        updateHistoryState() {
            this.canUndo = window.HistoryManager.canUndo();
            this.canRedo = window.HistoryManager.canRedo();
        },

        /**
         * Salva estado atual no histórico (deve ser chamado após cada mudança)
         */
        saveToHistory() {
            window.HistoryManager.save(this.blocks);
            this.updateHistoryState();
        }
    };
};
