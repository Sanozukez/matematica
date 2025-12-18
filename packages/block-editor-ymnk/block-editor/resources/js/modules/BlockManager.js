/**
 * Block Manager Module
 * 
 * Responsável por operações CRUD de blocos:
 * - Adicionar blocos
 * - Remover blocos
 * - Atualizar conteúdo e atributos
 * - Mover blocos (up/down)
 * - Duplicar blocos
 * 
 * Princípio SRP: Apenas gerenciamento de blocos
 */

window.BlockManager = {
    /**
     * Gera ID único para bloco
     */
    generateBlockId() {
        return `block_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
    },
    
    /**
     * Adiciona novo bloco
     */
    addBlock(blocks, type = 'paragraph', afterIndex = null) {
        const newBlock = {
            id: this.generateBlockId(),
            type: type,
            content: '',
            attributes: {}
        };

        // Columns: inicializa com 2 colunas vazias
        if (type === 'columns') {
            newBlock.attributes = { columnCount: 2 };
            newBlock.content = [
                { blocks: [] },
                { blocks: [] }
            ];
        }
        
        if (afterIndex !== null) {
            blocks.splice(afterIndex + 1, 0, newBlock);
        } else {
            blocks.push(newBlock);
        }
        
        return newBlock;
    },
    
    /**
     * Remove bloco
     */
    removeBlock(blocks, blockId) {
        const index = blocks.findIndex(b => b.id === blockId);
        if (index === -1) return null;
        
        // Se é o único bloco, apenas limpa o conteúdo
        if (blocks.length === 1) {
            blocks[0].content = '';
            return { action: 'cleared', blockId: blocks[0].id };
        }
        
        // Remove o bloco
        blocks.splice(index, 1);
        
        // Retorna índice para focar próximo bloco
        const newFocusIndex = index > 0 ? index - 1 : 0;
        return { 
            action: 'removed', 
            focusBlockId: blocks[newFocusIndex]?.id,
            focusIndex: newFocusIndex
        };
    },
    
    /**
     * Atualiza conteúdo de um bloco
     */
    updateBlockContent(blocks, blockId, content) {
        const block = blocks.find(b => b.id === blockId);
        if (block) {
            block.content = content;
            return true;
        }
        return false;
    },
    
    /**
     * Atualiza atributos de um bloco
     */
    updateBlockAttributes(blocks, blockId, attributes) {
        const block = blocks.find(b => b.id === blockId);
        if (block) {
            block.attributes = { ...block.attributes, ...attributes };
            return true;
        }
        return false;
    },
    
    /**
     * Move bloco para cima
     */
    moveBlockUp(blocks, blockId) {
        const index = blocks.findIndex(b => b.id === blockId);
        if (index > 0) {
            [blocks[index - 1], blocks[index]] = [blocks[index], blocks[index - 1]];
            return true;
        }
        return false;
    },
    
    /**
     * Move bloco para baixo
     */
    moveBlockDown(blocks, blockId) {
        const index = blocks.findIndex(b => b.id === blockId);
        if (index < blocks.length - 1) {
            [blocks[index], blocks[index + 1]] = [blocks[index + 1], blocks[index]];
            return true;
        }
        return false;
    },
    
    /**
     * Duplica um bloco
     */
    duplicateBlock(blocks, blockId) {
        const index = blocks.findIndex(b => b.id === blockId);
        if (index !== -1) {
            const originalBlock = blocks[index];
            const newBlock = JSON.parse(JSON.stringify(originalBlock));
            newBlock.id = this.generateBlockId();
            blocks.splice(index + 1, 0, newBlock);
            return newBlock;
        }
        return null;
    },
    
    /**
     * Encontra bloco por ID
     */
    findBlock(blocks, blockId) {
        return blocks.find(b => b.id === blockId);
    },
    
    /**
     * Encontra índice do bloco
     */
    findBlockIndex(blocks, blockId) {
        return blocks.findIndex(b => b.id === blockId);
    },
    
    /**
     * Serializa blocos para JSON
     */
    serializeBlocks(blocks) {
        return blocks.map(block => {
            // Colunas: serializa estrutura interna diretamente do estado
            if (block.type === 'columns') {
                return {
                    id: block.id,
                    type: block.type,
                    content: block.content || [],
                    attributes: block.attributes || {}
                };
            }

            const element = document.querySelector(`[data-block-id="${block.id}"]`);
            const editable = element?.querySelector('[contenteditable="true"]');
            
            return {
                id: block.id,
                type: block.type,
                content: editable ? editable.innerHTML : (block.content || ''),
                attributes: block.attributes || {}
            };
        });
    }
};
