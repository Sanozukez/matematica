/**
 * Block Renderers Module
 * 
 * Responsável por renderização específica de blocos complexos:
 * - Image upload
 * - Video embed (YouTube/Vimeo)
 * - LaTeX rendering
 * 
 * Princípio SRP: Apenas renderizadores específicos
 */

window.BlockRenderers = {
    /**
     * IMAGE BLOCK: Handle upload de imagem
     */
    handleImageUpload(event, blockId, updateContentCallback, saveCallback) {
        const file = event.target.files[0];
        if (!file) return false;
        
        // Validação
        if (!file.type.startsWith('image/')) {
            alert('Por favor, selecione um arquivo de imagem');
            return false;
        }
        
        // Converte para Base64 ou URL (produção: upload para servidor)
        const reader = new FileReader();
        reader.onload = (e) => {
            updateContentCallback(blockId, e.target.result);
            saveCallback();
        };
        reader.readAsDataURL(file);
        
        return true;
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
     * LATEX BLOCK: Renderiza LaTeX (placeholder - requer biblioteca KaTeX)
     */
    renderLatex(latex, mode = 'inline') {
        if (!latex) return '';
        
        // Em produção, usar KaTeX: katex.renderToString(latex, { displayMode: mode === 'block' })
        // Por enquanto, retorna placeholder estilizado
        const wrapper = mode === 'block' ? '$$' : '$';
        return `<code style="background: #F3F4F6; padding: 0.25rem 0.5rem; border-radius: 4px; font-family: 'Courier New', monospace;">${wrapper} ${latex} ${wrapper}</code>`;
    }
};
