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
 */
export default function BlockEditorCore() {
    return {
        // Estado
        blocks: [],
        focusedBlockId: null,
        lessonId: null,
        isSaving: false,
        
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
            
            // Carrega blocos salvos (se existir)
            this.loadBlocks();
            
            // Se não há blocos, mostra empty state
            if (this.blocks.length === 0) {
                console.log('Editor iniciado: canvas vazio');
            }
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
         * @param {string} type - Tipo do bloco (paragraph, heading, etc)
         * @param {number|null} afterIndex - Índice para inserir após (null = final)
         */
        addBlock(type = 'paragraph', afterIndex = null) {
            const newBlock = {
                id: this.generateBlockId(),
                type: type,
                content: '',
                order: this.blocks.length,
                attributes: {} // Metadados específicos do bloco (ex: level para heading)
            };
            
            if (afterIndex !== null) {
                // Insere após um bloco específico
                this.blocks.splice(afterIndex + 1, 0, newBlock);
                this.reorderBlocks();
            } else {
                // Adiciona no final
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
         * Atualiza atributos de um bloco
         */
        updateBlockAttributes(blockId, attributes) {
            const block = this.blocks.find(b => b.id === blockId);
            if (block) {
                block.attributes = { ...block.attributes, ...attributes };
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
                    // Move cursor para o final
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
         * Comportamento Gutenberg: criar novo bloco do mesmo tipo
         */
        handleEnter(event, blockId) {
            event.preventDefault();
            
            const block = this.blocks.find(b => b.id === blockId);
            if (!block) return;
            
            const selection = window.getSelection();
            const range = selection.getRangeAt(0);
            const element = event.target;
            
            // Captura texto após o cursor
            const textAfterCursor = this.getTextAfterCursor(element, range);
            
            // Se houver texto após cursor, move para novo bloco
            if (textAfterCursor) {
                // Remove texto após cursor do bloco atual
                range.deleteContents();
                const textBeforeCursor = element.textContent;
                this.updateBlockContent(blockId, textBeforeCursor);
            }
            
            // Cria novo bloco (mesmo tipo por padrão)
            const index = this.blocks.findIndex(b => b.id === blockId);
            const newBlock = this.addBlock(block.type, index);
            
            // Se havia texto após cursor, coloca no novo bloco
            if (textAfterCursor) {
                newBlock.content = textAfterCursor;
            }
        },
        
        /**
         * Handler de tecla Backspace
         * Comportamento Gutenberg: mesclar com bloco anterior se vazio
         */
        handleBackspace(event, blockId) {
            const block = this.blocks.find(b => b.id === blockId);
            if (!block) return;
            
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
         * Captura texto após o cursor
         */
        getTextAfterCursor(element, range) {
            const clone = range.cloneRange();
            clone.selectNodeContents(element);
            clone.setStart(range.endContainer, range.endOffset);
            return clone.toString();
        },
        
        /**
         * Reordena blocos após inserção/remoção
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
                    // Pode adicionar notificação de sucesso aqui
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
