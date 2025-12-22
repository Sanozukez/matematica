/**
 * State Manager Module
 * 
 * Responsável por gerenciamento de estado:
 * - Save/Load de blocos
 * - Debounce de auto-save
 * - Comunicação com API
 * 
 * Princípio SRP: Apenas persistência de estado
 */

window.StateManager = {
    /**
     * Carrega blocos do servidor
     */
    async loadBlocks(lessonId) {
        if (!lessonId) return null;
        
        try {
            const response = await fetch(`/api/lessons/${lessonId}/blocks`);
            
            // Verifica se sessão expirou (401/419)
            if (response.status === 401 || response.status === 419) {
                console.warn('⚠️ Sessão expirada, redirecionando para login...');
                window.location.href = '/admin/login';
                return null;
            }
            
            if (response.ok) {
                const data = await response.json();
                return {
                    blocks: data.blocks || [],
                    lessonTitle: data.lesson_title || '',
                    lesson: data.lesson || null // Dados completos da lesson
                };
            }
        } catch (error) {
            console.log('Nenhum bloco salvo ainda');
        }
        
        return null;
    },
    
    /**
     * Salva blocos no servidor com HTML completo serializado
     */
    async saveBlocks(lessonId, blocks, lessonTitle) {
        if (!lessonId) {
            console.error('ID da lesson não encontrado');
            return { success: false, error: 'no-lesson-id' };
        }
        
        try {
            // Serializa blocos com HTML completo (innerHTML dos contenteditable)
            const serializedBlocks = blocks.map(block => {
                const element = document.querySelector(`[data-block-id="${block.id}"]`);
                const editable = element?.querySelector('[contenteditable="true"]');
                
                // Para blocos de colunas, serializar os blocos internos
                let serializedBlock = {
                    id: block.id,
                    type: block.type,
                    content: editable ? editable.innerHTML : (block.content || ''),
                    attributes: block.attributes || {}
                };
                
                // Se for coluna, incluir os blocos internos com conteúdo serializado
                if (block.type === 'columns' && block.attributes?.columns) {
                    serializedBlock.attributes.columns = block.attributes.columns.map(col => ({
                        blocks: (col.blocks || []).map(innerBlock => {
                            // Busca elemento do bloco interno
                            const innerElement = document.querySelector(`[data-inner-block-id="${innerBlock.id}"]`);
                            const innerEditable = innerElement?.querySelector('[contenteditable="true"]');
                            
                            return {
                                id: innerBlock.id,
                                type: innerBlock.type,
                                content: innerEditable ? innerEditable.innerHTML : (innerBlock.content || ''),
                                attributes: innerBlock.attributes || {}
                            };
                        })
                    }));
                }
                
                return serializedBlock;
            });
            
            const response = await fetch(`/api/lessons/${lessonId}/blocks`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ 
                    blocks: serializedBlocks,
                    lesson_title: lessonTitle
                })
            });
            
            // Verifica se sessão expirou (401/419)
            if (response.status === 401 || response.status === 419) {
                console.warn('⚠️ Sessão expirada, redirecionando para login...');
                window.location.href = '/admin/login';
                return { success: false, error: 'session-expired' };
            }
            
            if (response.ok) {
                console.log('✅ Salvamento bem-sucedido');
                return { success: true };
            } else {
                const errorData = await response.json().catch(() => null);
                console.error('❌ Erro ao salvar, status:', response.status);
                console.error('Detalhes do erro:', errorData);
                return { success: false, error: 'server-error', status: response.status, details: errorData };
            }
        } catch (error) {
            console.error('Erro ao salvar:', error);
            return { success: false, error: 'network-error', details: error };
        }
    },
    
    /**
     * Cria um debouncer para auto-save
     */
    createDebouncer() {
        let timeoutId = null;
        
        return {
            debounce(callback, delay = 180000) {
                if (timeoutId) {
                    clearTimeout(timeoutId);
                }
                
                timeoutId = setTimeout(() => {
                    callback();
                    timeoutId = null;
                }, delay);
            },
            
            clear() {
                if (timeoutId) {
                    clearTimeout(timeoutId);
                    timeoutId = null;
                }
            }
        };
    }
};
