/**
 * Format Manager Module
 * 
 * Responsável por formatação de texto:
 * - Bold, Italic, Underline
 * - Cores de texto
 * - Alinhamento
 * - Links
 * 
 * Princípio SRP: Apenas formatação
 */

window.FormatManager = {
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
     * Aplica cor de texto ao bloco (classe Tailwind)
     */
    applyTextColor(blockId, colorClass, blocks) {
        const block = blocks.find(b => b.id === blockId);
        if (!block) return false;
        
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
        
        return true;
    },
    
    /**
     * Aplica alinhamento ao bloco
     */
    applyAlignment(blockId, alignment, blocks) {
        const block = blocks.find(b => b.id === blockId);
        if (!block) return false;
        
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
        
        return true;
    },
    
    /**
     * Insere link no texto selecionado
     */
    insertLink(blockId, url) {
        if (!url) return false;
        
        const element = document.querySelector(`[data-block-id="${blockId}"]`);
        if (element) {
            const editable = element.querySelector('[contenteditable="true"]');
            if (editable) {
                editable.focus();
                document.execCommand('createLink', false, url);
                return true;
            }
        }
        return false;
    }
};
