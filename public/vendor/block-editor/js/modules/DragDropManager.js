/**
 * Drag & Drop Manager Module
 * 
 * Responsável por operações de arrastar e soltar:
 * - Estado do drag
 * - Handlers de drag events
 * - Reordenação de blocos
 * 
 * Princípio SRP: Apenas drag & drop
 */

window.DragDropManager = {
    // Estado do drag
    dragState: {
        draggingBlockId: null,
        dragOverBlockId: null,
        dragStartY: 0,
        currentY: 0
    },
    
    /**
     * Inicia arraste
     */
    handleDragStart(event, blockId) {
        this.dragState.draggingBlockId = blockId;
        this.dragState.dragStartY = event.clientY;
        
        // Visual feedback
        const wrapper = event.target.closest('.block-wrapper');
        if (wrapper) {
            wrapper.style.opacity = '0.5';
        }
        
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData('text/html', event.target.innerHTML);
    },
    
    /**
     * Sobre bloco alvo
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
     * Sai do bloco alvo
     */
    handleDragLeave(event) {
        const targetElement = event.target.closest('.block-wrapper');
        if (targetElement) {
            targetElement.style.borderTop = '';
        }
    },
    
    /**
     * Solta bloco
     */
    handleDrop(event, targetBlockId, blocks) {
        event.preventDefault();
        
        const draggingBlockId = this.dragState.draggingBlockId;
        if (!draggingBlockId || draggingBlockId === targetBlockId) {
            this.resetDragState();
            return false;
        }
        
        // Encontra índices
        const fromIndex = blocks.findIndex(b => b.id === draggingBlockId);
        const toIndex = blocks.findIndex(b => b.id === targetBlockId);
        
        if (fromIndex === -1 || toIndex === -1) {
            this.resetDragState();
            return false;
        }
        
        // Move bloco no array
        const [movedBlock] = blocks.splice(fromIndex, 1);
        blocks.splice(toIndex, 0, movedBlock);
        
        this.resetDragState();
        return true;
    },
    
    /**
     * Finaliza arraste
     */
    handleDragEnd(event) {
        const wrapper = event.target.closest('.block-wrapper');
        if (wrapper) {
            wrapper.style.opacity = '';
        }
        this.resetDragState();
    },
    
    /**
     * Reseta estado
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
