/**
 * Block Editor Core - Gutenberg-style Block Management
 * 
 * Responsável por:
 * - Gerenciar estado dos blocos (CRUD)
 * - Controlar foco e navegação entre blocos
 * - Serializar/deserializar JSON
 * - Listeners de teclado (Enter, Backspace, Arrow keys)
 * 
 * Princípio SRP: Apenas lógica de blocos, sem UI direta
 * 
 * Exposto globalmente como window.BlockEditorCore
 */

window.BlockEditorCore = function() {
    return {
        // Estado
        blocks: [],
        focusedBlockId: null,
        lessonId: null,
        lessonTitle: '',
        isSaving: false,
        hasChanges: false,
        showSaveToast: false,
        saveToastMessage: '',
        showBlockInserter: false,
        blockSearchQuery: '',
        canvasShifted: false, // Canvas empurrado para direita
        
        // Tipos de blocos disponíveis
        blockTypes: window.BLOCK_TYPES,
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
         * Debounce para auto-save (3 minutos após parar de editar)
         */
        debouncedSave() {
            this.hasChanges = true;
            clearTimeout(this._saveTimeout);
            this._saveTimeout = setTimeout(() => {
                if (this.hasChanges) {
                    this.saveBlocks();
                }
            }, 180000); // 3 minutos = 180.000ms
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
         * Insere bloco a partir do sidebar (NÃO fecha sidebar)
         */
        insertBlockFromModal(type) {
            this.addBlock(type);
            // Sidebar permanece aberta (comportamento WordPress)
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
                    this.lessonTitle = data.lesson_title || '';
                    this.hasChanges = false;
                }
            } catch (error) {
                console.log('Nenhum bloco salvo ainda');
            }
        },
        
        /**
         * Salva blocos no servidor com HTML completo serializado
         */
        async saveBlocks() {
            if (!this.lessonId || this.isSaving) return;
            
            this.isSaving = true;
            
            try {
                // Serializa blocos com HTML completo (innerHTML dos contenteditable)
                const serializedBlocks = this.blocks.map(block => {
                    const element = document.querySelector(`[data-block-id="${block.id}"]`);
                    const editable = element?.querySelector('[contenteditable="true"]');
                    
                    return {
                        id: block.id,
                        type: block.type,
                        content: editable ? editable.innerHTML : (block.content || ''),
                        attributes: block.attributes || {}
                    };
                });
                
                const response = await fetch(`/api/lessons/${this.lessonId}/blocks`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ 
                        blocks: serializedBlocks,
                        lesson_title: this.lessonTitle
                    })
                });
                
                if (response.ok) {
                    this.hasChanges = false;
                    console.log('✅ Salvamento bem-sucedido, chamando showToast...');
                    this.showToast('Salvo com sucesso!', 'success');
                } else {
                    console.error('❌ Erro ao salvar, status:', response.status);
                    this.showToast('Erro ao salvar', 'error');
                }
            } catch (error) {
                console.error('Erro ao salvar:', error);
                this.showToast('Erro ao salvar', 'error');
            } finally {
                this.isSaving = false;
            }
        },
        
        /**
         * Exibe toaster de feedback
         */
        showToast(message, type = 'success') {
            console.log('🔔 showToast chamado:', message, 'showSaveToast:', this.showSaveToast);
            this.saveToastMessage = message;
            this.showSaveToast = true;
            
            console.log('🔔 Após set: showSaveToast =', this.showSaveToast, 'message =', this.saveToastMessage);
            
            setTimeout(() => {
                this.showSaveToast = false;
                console.log('🔔 Toaster ocultado após 3s');
            }, 3000);
        },
        
        /**
         * Adiciona novo bloco
         */
        addBlock(type = 'paragraph', afterIndex = null) {
            const newBlock = {
                id: this.generateBlockId(),
                type: type,
                content: '',
                attributes: {}
            };
            
            if (afterIndex !== null) {
                this.blocks.splice(afterIndex + 1, 0, newBlock);
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
         * Atualiza atributos de um bloco
         */
        updateBlockAttributes(blockId, attributes) {
            const block = this.blocks.find(b => b.id === blockId);
            if (block) {
                block.attributes = { ...block.attributes, ...attributes };
            }
        },
        
        /**
         * Aplica cor de texto ao bloco (classe Tailwind)
         */
        applyTextColor(blockId, colorClass) {
            const block = this.blocks.find(b => b.id === blockId);
            if (!block) return;
            
            // Atualiza atributos do bloco
            if (!block.attributes) block.attributes = {};
            block.attributes.textColor = colorClass;
            
            // Aplica cor na seleção de texto (ou todo o bloco se não houver seleção)
            const element = document.querySelector(`[data-block-id="${blockId}"]`);
            if (element) {
                const editable = element.querySelector('[contenteditable="true"]');
                if (editable) {
                    editable.focus();
                    const selection = window.getSelection();
                    
                    // Se há texto selecionado, aplica cor apenas nele
                    if (selection.rangeCount > 0 && !selection.isCollapsed) {
                        const range = selection.getRangeAt(0);
                        const selectedText = range.toString();
                        
                        if (selectedText.trim()) {
                            // Remove cor anterior (se houver span com classe text-*)
                            const span = document.createElement('span');
                            span.className = colorClass || '';
                            span.textContent = selectedText;
                            
                            range.deleteContents();
                            range.insertNode(span);
                            
                            // Limpa seleção
                            selection.removeAllRanges();
                        }
                    } else {
                        // Sem seleção: aplica cor a todo o bloco
                        const colorClasses = Array.from(editable.classList).filter(c => c.startsWith('text-'));
                        colorClasses.forEach(c => editable.classList.remove(c));
                        
                        if (colorClass) {
                            editable.classList.add(colorClass);
                        }
                    }
                }
            }
        },
        
        /**
         * Aplica formatação (bold, italic, underline, etc)
         */
        applyFormatting(blockId, format) {
            const element = document.querySelector(`[data-block-id="${blockId}"]`);
            if (element) {
                const editable = element.querySelector('[contenteditable="true"]');
                if (editable) {
                    editable.focus();
                    document.execCommand(format, false, null);
                }
            }
        },
        
        /**
         * Aplica alinhamento ao bloco
         */
        applyAlignment(blockId, alignment) {
            const block = this.blocks.find(b => b.id === blockId);
            if (!block) return;
            
            if (!block.attributes) block.attributes = {};
            block.attributes.alignment = alignment;
            
            const element = document.querySelector(`[data-block-id="${blockId}"]`);
            if (element) {
                const editable = element.querySelector('[contenteditable="true"]');
                if (editable) {
                    ['text-left', 'text-center', 'text-right', 'text-justify'].forEach(c => editable.classList.remove(c));
                    if (alignment) {
                        editable.classList.add(`text-${alignment}`);
                    }
                }
            }
        },
        
        /**
         * Move bloco para cima
         */
        moveBlockUp(blockId) {
            const index = this.blocks.findIndex(b => b.id === blockId);
            if (index > 0) {
                [this.blocks[index - 1], this.blocks[index]] = [this.blocks[index], this.blocks[index - 1]];
            }
        },
        
        /**
         * Move bloco para baixo
         */
        moveBlockDown(blockId) {
            const index = this.blocks.findIndex(b => b.id === blockId);
            if (index < this.blocks.length - 1) {
                [this.blocks[index], this.blocks[index + 1]] = [this.blocks[index + 1], this.blocks[index]];
            }
        },
        
        /**
         * Duplica um bloco
         */
        duplicateBlock(blockId) {
            const index = this.blocks.findIndex(b => b.id === blockId);
            if (index !== -1) {
                const originalBlock = this.blocks[index];
                const newBlock = JSON.parse(JSON.stringify(originalBlock));
                newBlock.id = Date.now();
                this.blocks.splice(index + 1, 0, newBlock);
            }
        },
        
        /**
         * Insere link no texto selecionado
         */
        insertLink(blockId, url) {
            if (!url) return;
            
            const element = document.querySelector(`[data-block-id="${blockId}"]`);
            if (element) {
                const editable = element.querySelector('[contenteditable="true"]');
                if (editable) {
                    editable.focus();
                    document.execCommand('createLink', false, url);
                }
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
         * Gera ID único para bloco
         */
        generateBlockId() {
            return `block_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
        },
        
        /**
         * Desseleciona bloco ativo
         */
        deselectBlock() {
            this.focusedBlockId = null;
        },
        
        /**
         * Handler de clique no canvas
         * Adiciona parágrafo ao clicar abaixo do último bloco
         */
        handleCanvasClick(event) {
            // Se clicou diretamente no container de blocos (não em um bloco filho)
            if (event.target.classList.contains('block-editor-blocks')) {
                // Verifica se último bloco é parágrafo vazio
                const lastBlock = this.blocks[this.blocks.length - 1];
                
                // Se último bloco é parágrafo vazio, não adiciona novo
                if (lastBlock && lastBlock.type === 'paragraph' && !lastBlock.content.trim()) {
                    this.focusBlock(lastBlock.id);
                    return;
                }
                
                // Adiciona novo parágrafo
                this.addBlock('paragraph');
            }
        },
        
        /**
         * Serializa blocos para JSON (estrutura simplificada)
         * Ordem é implícita pelo índice do array
         */
        toJSON() {
            return {
                blocks: this.blocks.map(block => ({
                    id: block.id,
                    type: block.type,
                    content: block.content,
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
        },
        
        /**
         * IMAGE BLOCK: Handle upload de imagem
         */
        handleImageUpload(event, blockId) {
            const file = event.target.files[0];
            if (!file) return;
            
            // Validação
            if (!file.type.startsWith('image/')) {
                alert('Por favor, selecione um arquivo de imagem');
                return;
            }
            
            // Converte para Base64 ou URL (produção: upload para servidor)
            const reader = new FileReader();
            reader.onload = (e) => {
                this.updateBlockContent(blockId, e.target.result);
                this.debouncedSave();
            };
            reader.readAsDataURL(file);
        },
        
        /**
         * VIDEO BLOCK: Gera embed do YouTube/Vimeo
         */
        getVideoEmbed(url) {
            if (!url) return '';
            
            // YouTube
            const youtubeMatch = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/);
            if (youtubeMatch) {
                return `<iframe src="https://www.youtube.com/embed/${youtubeMatch[1]}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
            }
            
            // Vimeo
            const vimeoMatch = url.match(/vimeo\.com\/(\d+)/);
            if (vimeoMatch) {
                return `<iframe src="https://player.vimeo.com/video/${vimeoMatch[1]}" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>`;
            }
            
            return '<p style="color: #EF4444; padding: 1rem;">URL de vídeo inválida. Use YouTube ou Vimeo.</p>';
        },
        
        /**
         * LIST BLOCK: Atualiza item da lista
         */
        updateListItem(blockId, index, value) {
            const block = this.blocks.find(b => b.id === blockId);
            if (!block) return;
            
            if (!Array.isArray(block.content)) {
                block.content = [''];
            }
            
            block.content[index] = value;
            this.debouncedSave();
        },
        
        /**
         * LIST BLOCK: Adiciona novo item
         */
        addListItem(blockId, afterIndex) {
            const block = this.blocks.find(b => b.id === blockId);
            if (!block) return;
            
            if (!Array.isArray(block.content)) {
                block.content = [''];
            }
            
            block.content.splice(afterIndex + 1, 0, '');
            
            // Foca no novo item após renderização
            this.$nextTick(() => {
                const listItems = document.querySelectorAll(`[data-block-id="${blockId}"] .block-list-item`);
                if (listItems[afterIndex + 1]) {
                    listItems[afterIndex + 1].focus();
                }
            });
        },
        
        /**
         * LIST BLOCK: Remove item se vazio e backspace
         */
        handleListBackspace(event, blockId, index) {
            const block = this.blocks.find(b => b.id === blockId);
            if (!block || !Array.isArray(block.content)) return;
            
            const item = event.target.textContent.trim();
            const isAtStart = window.getSelection().anchorOffset === 0;
            
            if (isAtStart && !item) {
                event.preventDefault();
                
                // Se é último item, remove bloco
                if (block.content.length === 1) {
                    this.removeBlock(blockId);
                } else {
                    // Remove item
                    block.content.splice(index, 1);
                    
                    // Foca no item anterior
                    this.$nextTick(() => {
                        const listItems = document.querySelectorAll(`[data-block-id="${blockId}"] .block-list-item`);
                        if (listItems[index - 1]) {
                            listItems[index - 1].focus();
                        }
                    });
                }
            }
        },
        
        /**
         * TABLE BLOCK: Atualiza célula
         */
        updateTableCell(blockId, rowIndex, colIndex, value) {
            const block = this.blocks.find(b => b.id === blockId);
            if (!block) return;
            
            if (!Array.isArray(block.content)) {
                block.content = [
                    ['Cabeçalho 1', 'Cabeçalho 2', 'Cabeçalho 3'],
                    ['', '', '']
                ];
            }
            
            if (!block.content[rowIndex]) {
                block.content[rowIndex] = [];
            }
            
            block.content[rowIndex][colIndex] = value;
            this.debouncedSave();
        },
        
        /**
         * LATEX BLOCK: Renderiza LaTeX (placeholder - requer biblioteca KaTeX)
         */
        renderLatex(latex, mode = 'inline') {
            if (!latex) return '';
            
            // Em produção, usar KaTeX: katex.renderToString(latex, { displayMode: mode === 'block' })
            // Por enquanto, retorna placeholder estilizado
            const wrapper = mode === 'block' ? '$$' : '$';
            return `<code style="background: #F3F4F6; padding: 0.25rem 0.5rem; border-radius: 4px; font-family: 'Courier New', monospace;">${wrapper} ${latex} ${wrapper}</code>`;
        },
        
        /**
         * DRAG & DROP: Estado do drag
         */
        dragState: {
            draggingBlockId: null,
            dragOverBlockId: null,
            dragStartY: 0,
            currentY: 0
        },
        
        /**
         * DRAG & DROP: Inicia arraste
         */
        handleDragStart(event, blockId) {
            this.dragState.draggingBlockId = blockId;
            this.dragState.dragStartY = event.clientY;
            
            // Visual feedback
            event.target.closest('.block-wrapper').style.opacity = '0.5';
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/html', event.target.innerHTML);
        },
        
        /**
         * DRAG & DROP: Sobre bloco alvo
         */
        handleDragOver(event, blockId) {
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
            
            this.dragState.dragOverBlockId = blockId;
            this.dragState.currentY = event.clientY;
            
            // Visual feedback
            const targetElement = event.target.closest('.block-wrapper');
            if (targetElement && this.dragState.draggingBlockId !== blockId) {
                targetElement.style.borderTop = '2px solid #007CBA';
            }
        },
        
        /**
         * DRAG & DROP: Sai do bloco alvo
         */
        handleDragLeave(event) {
            const targetElement = event.target.closest('.block-wrapper');
            if (targetElement) {
                targetElement.style.borderTop = '';
            }
        },
        
        /**
         * DRAG & DROP: Solta bloco
         */
        handleDrop(event, targetBlockId) {
            event.preventDefault();
            
            const draggingBlockId = this.dragState.draggingBlockId;
            if (!draggingBlockId || draggingBlockId === targetBlockId) {
                this.resetDragState();
                return;
            }
            
            // Encontra índices
            const fromIndex = this.blocks.findIndex(b => b.id === draggingBlockId);
            const toIndex = this.blocks.findIndex(b => b.id === targetBlockId);
            
            if (fromIndex === -1 || toIndex === -1) {
                this.resetDragState();
                return;
            }
            
            // Move bloco no array
            const [movedBlock] = this.blocks.splice(fromIndex, 1);
            this.blocks.splice(toIndex, 0, movedBlock);
            
            this.resetDragState();
            this.debouncedSave();
        },
        
        /**
         * DRAG & DROP: Finaliza arraste
         */
        handleDragEnd(event) {
            event.target.closest('.block-wrapper').style.opacity = '';
            this.resetDragState();
        },
        
        /**
         * DRAG & DROP: Reseta estado
         */
        resetDragState() {
            // Remove visual feedback de todos os blocos
            document.querySelectorAll('.block-wrapper').forEach(el => {
                el.style.borderTop = '';
                el.style.opacity = '';
            });
            
            this.dragState.draggingBlockId = null;
            this.dragState.dragOverBlockId = null;
            this.dragState.dragStartY = 0;
            this.dragState.currentY = 0;
        }
    };
};
