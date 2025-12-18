/**
 * Slash Commands Module
 * 
 * Responsável por comandos /para, /titulo, etc (estilo WordPress/Notion)
 * - Detecta quando usuário digita "/"
 * - Mostra menu de blocos filtrados
 * - Substitui texto pelo bloco escolhido
 * 
 * Princípio SRP: Apenas slash commands
 */

window.SlashCommands = {
    /**
     * Extrai comando do texto (ex: "/para" retorna "para")
     */
    extractCommand(text) {
        const match = text.match(/\/(\w+)$/);
        return match ? match[1].toLowerCase() : null;
    },
    
    /**
     * Verifica se deve mostrar menu de comandos
     */
    shouldShowMenu(text) {
        return text.endsWith('/') || /\/\w+$/.test(text);
    },
    
    /**
     * Filtra blocos por comando digitado
     */
    filterBlocksByCommand(blockTypes, command) {
        if (!command || command === '') {
            return blockTypes; // Mostra todos se só digitou "/"
        }
        
        return blockTypes.filter(block => {
            const label = block.label.toLowerCase();
            const type = block.type.toLowerCase();
            const desc = block.description.toLowerCase();
            const cmd = command.toLowerCase();
            
            // Busca em label, type ou description
            return label.includes(cmd) || 
                   type.includes(cmd) || 
                   desc.includes(cmd) ||
                   this.getSlashAliases(block.type).some(alias => alias.includes(cmd));
        });
    },
    
    /**
     * Aliases de comandos (ex: /h para heading)
     */
    getSlashAliases(blockType) {
        const aliases = {
            'paragraph': ['para', 'p', 'texto', 'text'],
            'heading': ['h', 'titulo', 'title', 'cabecalho'],
            'h1': ['h1', 'titulo1'],
            'h2': ['h2', 'titulo2'],
            'h3': ['h3', 'titulo3'],
            'quote': ['q', 'citacao', 'citação', 'citar'],
            'code': ['cod', 'codigo', 'código', 'pre'],
            'image': ['img', 'imagem', 'foto', 'picture'],
            'video': ['vid', 'video', 'vídeo', 'youtube', 'vimeo'],
            'list': ['li', 'lista', 'ul', 'ol'],
            'alert': ['aviso', 'alerta', 'warning', 'info'],
            'latex': ['math', 'matematica', 'matemática', 'formula', 'fórmula', 'equacao', 'equação'],
            'table': ['tab', 'tabela', 'grid'],
            'divider': ['div', 'divisor', 'linha', 'hr', 'separador']
        };
        
        return aliases[blockType] || [];
    },
    
    /**
     * Remove comando do texto (ex: "Olá /para" -> "Olá ")
     */
    removeCommandFromText(text) {
        return text.replace(/\/\w*$/, '');
    },
    
    /**
     * Verifica se bloco está vazio (apenas comando)
     */
    isBlockOnlyCommand(text) {
        return /^\/\w*$/.test(text.trim());
    },
    
    /**
     * Posição do cursor relativa ao bloco (para posicionar menu)
     */
    getCursorPosition(element) {
        const selection = window.getSelection();
        if (!selection.rangeCount) return null;
        
        const range = selection.getRangeAt(0);
        const rect = range.getBoundingClientRect();
        const blockRect = element.getBoundingClientRect();
        
        return {
            x: rect.left - blockRect.left,
            y: rect.bottom - blockRect.top,
            absolute: {
                x: rect.left,
                y: rect.bottom
            }
        };
    }
};
