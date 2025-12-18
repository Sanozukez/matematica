/**
 * Event Handlers Module
 * 
 * Responsável por handlers de eventos:
 * - Teclado (Enter, Backspace, Arrow keys)
 * - Mouse (clicks, focus)
 * - Navegação entre blocos
 * 
 * Princípio SRP: Apenas eventos e navegação
 */

window.EventHandlers = {
    /**
     * Handler de tecla Enter
     * Cria novo bloco abaixo do atual
     */
    handleEnter(event, blocks, blockId, addBlockCallback) {
        event.preventDefault();
        
        const block = blocks.find(b => b.id === blockId);
        if (!block) return;
        
        // Cria novo bloco
        const index = blocks.findIndex(b => b.id === blockId);
        return addBlockCallback(block.type, index);
    },
    
    /**
     * Handler de tecla Backspace
     * Remove bloco se vazio e cursor no início
     */
    handleBackspace(event, blockId, removeBlockCallback) {
        const element = event.target;
        const selection = window.getSelection();
        const isAtStart = selection.anchorOffset === 0;
        
        // Se cursor está no início e conteúdo vazio, remove bloco
        if (isAtStart && !element.textContent.trim()) {
            event.preventDefault();
            removeBlockCallback(blockId);
        }
    },
    
    /**
     * Handler de clique no canvas
     * Adiciona parágrafo SOMENTE ao clicar ABAIXO do último bloco (área vazia)
     */
    handleCanvasClick(event, blocks, addBlockCallback, focusBlockCallback) {
        // Se clicou diretamente no container de blocos (não em um bloco filho)
        if (event.target.classList.contains('block-editor-blocks')) {
            // Pega posição do clique e posição do último bloco
            const clickY = event.clientY;
            const blocksContainer = event.target;
            const allBlocks = blocksContainer.querySelectorAll('.block-wrapper');
            
            if (allBlocks.length === 0) {
                // Se não há blocos, adiciona um parágrafo
                addBlockCallback('paragraph');
                return;
            }
            
            const lastBlockElement = allBlocks[allBlocks.length - 1];
            const lastBlockRect = lastBlockElement.getBoundingClientRect();
            const lastBlockBottom = lastBlockRect.bottom;
            
            // Só adiciona se clicou ABAIXO do último bloco (com margem de 20px)
            if (clickY > lastBlockBottom + 20) {
                const lastBlock = blocks[blocks.length - 1];
                
                // Se último bloco é parágrafo vazio, foca nele ao invés de adicionar novo
                if (lastBlock && lastBlock.type === 'paragraph' && !lastBlock.content.trim()) {
                    focusBlockCallback(lastBlock.id);
                    return;
                }
                
                // Adiciona novo parágrafo
                addBlockCallback('paragraph');
            }
        }
    },
    
    /**
     * Foca em um bloco específico
     */
    focusBlock(blockId) {
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
     * LIST BLOCK: Adiciona novo item
     */
    addListItem(blockId, afterIndex, blocks) {
        const block = blocks.find(b => b.id === blockId);
        if (!block) return false;
        
        if (!Array.isArray(block.content)) {
            block.content = [''];
        }
        
        block.content.splice(afterIndex + 1, 0, '');
        return true;
    },
    
    /**
     * LIST BLOCK: Remove item se vazio e backspace
     */
    handleListBackspace(event, blockId, index, blocks, removeBlockCallback) {
        const block = blocks.find(b => b.id === blockId);
        if (!block || !Array.isArray(block.content)) return;
        
        const item = event.target.textContent.trim();
        const isAtStart = window.getSelection().anchorOffset === 0;
        
        if (isAtStart && !item) {
            event.preventDefault();
            
            // Se é último item, remove bloco
            if (block.content.length === 1) {
                removeBlockCallback(blockId);
                return { action: 'remove-block' };
            } else {
                // Remove item
                block.content.splice(index, 1);
                return { action: 'remove-item', focusIndex: index - 1 };
            }
        }
        return null;
    },
    
    /**
     * LIST BLOCK: Atualiza item da lista
     */
    updateListItem(blockId, index, value, blocks) {
        const block = blocks.find(b => b.id === blockId);
        if (!block) return false;
        
        if (!Array.isArray(block.content)) {
            block.content = [''];
        }
        
        block.content[index] = value;
        return true;
    },
    
    /**
     * TABLE BLOCK: Atualiza célula
     */
    updateTableCell(blockId, rowIndex, colIndex, value, blocks) {
        const block = blocks.find(b => b.id === blockId);
        if (!block) return false;
        
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
        return true;
    }
};
