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

import { BLOCK_TYPES } from './block-types.js';

export default function BlockEditorCore() {
    return {
        // Estado
        blocks: [],
        focusedBlockId: null,
        lessonId: null,
        isSaving: false,
        showBlockInserter: false,
        blockSearchQuery: '',
        canvasShifted: false, // Canvas empurrado para direita
        
        // Tipos de blocos disponíveis
        blockTypes: BLOCK_TYPES,
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
         * Abre sidebar de blocos e empurra canvas
         */
        openBlockInserter() {
            this.showBlockInserter = true;
            this.canvasShifted = true;
            this.blockSearchQuery = '';
            this.filteredBlockTypes = [...this.blockTypes];
        },
        
        /**
         * Fecha sidebar e retorna canvas
         */
        closeBlockInserter() {
            this.showBlockInserter = false;
            this.canvasShifted = false;
        },
        
        /**
         * Insere bloco a partir do modal
         */
        insertBlockFromModal(type) {
            this.addBlock(type);
            this.closeBlockInserter();
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
        }
    };
}

// Exporta para uso global (Alpine.js)
window.BlockEditorCore = BlockEditorCore;
