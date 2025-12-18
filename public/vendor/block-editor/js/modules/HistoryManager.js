/**
 * History Manager - Undo/Redo functionality
 * 
 * Mantém histórico de mudanças e permite navegar entre elas
 */

window.HistoryManager = {
    history: [],
    currentIndex: -1,
    maxHistory: 50,

    /**
     * Salva estado atual no histórico
     */
    save(blocks) {
        // Remove qualquer estado que estava à frente (após undo)
        if (this.currentIndex < this.history.length - 1) {
            this.history = this.history.slice(0, this.currentIndex + 1);
        }

        // Adiciona novo estado
        this.history.push(JSON.parse(JSON.stringify(blocks)));
        this.currentIndex++;

        // Limita tamanho do histórico
        if (this.history.length > this.maxHistory) {
            this.history.shift();
            this.currentIndex--;
        }
    },

    /**
     * Retorna ao estado anterior (undo)
     */
    undo() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
            return JSON.parse(JSON.stringify(this.history[this.currentIndex]));
        }
        return null;
    },

    /**
     * Avança ao próximo estado (redo)
     */
    redo() {
        if (this.currentIndex < this.history.length - 1) {
            this.currentIndex++;
            return JSON.parse(JSON.stringify(this.history[this.currentIndex]));
        }
        return null;
    },

    /**
     * Verifica se pode fazer undo
     */
    canUndo() {
        return this.currentIndex > 0;
    },

    /**
     * Verifica se pode fazer redo
     */
    canRedo() {
        return this.currentIndex < this.history.length - 1;
    },

    /**
     * Limpa histórico
     */
    clear() {
        this.history = [];
        this.currentIndex = -1;
    }
};
