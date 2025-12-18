/**
 * Block Transform Module
 * 
 * Responsável por transformar blocos de um tipo para outro
 * - Paragraph <-> Heading <-> Quote
 * - Preserva conteúdo quando possível
 * - Limpa formatação incompatível
 * 
 * Princípio SRP: Apenas transformação de blocos
 */

window.BlockTransform = {
    /**
     * Verifica se transformação é possível
     */
    canTransform(fromType, toType) {
        // Blocos de texto podem ser transformados entre si
        const textBlocks = ['paragraph', 'heading', 'quote'];
        
        if (textBlocks.includes(fromType) && textBlocks.includes(toType)) {
            return true;
        }
        
        // Outros tipos só podem ser substituídos (não transformados)
        return false;
    },
    
    /**
     * Transforma bloco mantendo conteúdo
     */
    transform(block, toType, element) {
        const fromType = block.type;
        
        // Se pode transformar (blocos de texto)
        if (this.canTransform(fromType, toType)) {
            // Pega conteúdo atual
            const editable = element?.querySelector('[contenteditable="true"]');
            const content = editable ? editable.innerHTML : block.content;
            
            // Atualiza tipo e mantém conteúdo
            block.type = toType;
            block.content = content;
            
            // Limpa atributos que não fazem sentido no novo tipo
            if (toType === 'heading' && block.attributes?.level === undefined) {
                block.attributes = { ...block.attributes, level: 2 };
            }
            
            return { success: true, action: 'transformed' };
        }
        
        // Se não pode transformar, substitui
        return { success: false, action: 'replace', reason: 'incompatible' };
    },
    
    /**
     * Lista de transformações possíveis para um tipo
     */
    getTransformOptions(blockType) {
        const transforms = {
            'paragraph': [
                { type: 'heading', label: 'Título', icon: '🔤' },
                { type: 'quote', label: 'Citação', icon: '💬' },
                { type: 'list', label: 'Lista', icon: '📝' },
                { type: 'code', label: 'Código', icon: '💻' }
            ],
            'heading': [
                { type: 'paragraph', label: 'Parágrafo', icon: '¶' },
                { type: 'quote', label: 'Citação', icon: '💬' }
            ],
            'quote': [
                { type: 'paragraph', label: 'Parágrafo', icon: '¶' },
                { type: 'heading', label: 'Título', icon: '🔤' }
            ],
            'code': [
                { type: 'paragraph', label: 'Parágrafo', icon: '¶' }
            ],
            'list': [
                { type: 'paragraph', label: 'Parágrafo', icon: '¶' }
            ]
        };
        
        return transforms[blockType] || [];
    },
    
    /**
     * Preserva formatação ao transformar
     */
    preserveFormatting(content, fromType, toType) {
        // Remove formatações incompatíveis
        let cleaned = content;
        
        // Se indo para code, remove todas as tags HTML
        if (toType === 'code') {
            const temp = document.createElement('div');
            temp.innerHTML = content;
            cleaned = temp.textContent || temp.innerText;
        }
        
        return cleaned;
    }
};
