/**
 * Lafily Block Editor - JavaScript Main Bundle
 * Módulo principal que orquestra todo o editor
 */

class LafilyBlockEditor {
    constructor() {
        this.state = {
            selectedBlock: null,
            blocks: [],
            settings: {
                autosave: true,
                debounce: 1000,
            },
        };
        this.timers = {};
    }

    /**
     * Inicializa o editor
     */
    init() {
        console.log('[Lafily] Inicializando Block Editor...');
        
        this.setupDOM();
        this.setupEventListeners();
        this.loadBlockInserts();
        this.setupResponsive();
        this.setupTabs();
        
        console.log('[Lafily] Block Editor pronto!');
    }

    /**
     * Obtém referências aos elementos do DOM
     */
    setupDOM() {
        this.elements = {
            wrapper: document.querySelector('.block-editor-wrapper'),
            topbar: document.querySelector('.block-editor-topbar'),
            inserter: document.querySelector('.block-editor-inserter'),
            inserterList: document.querySelector('#inserter-list'),
            canvas: document.querySelector('.block-editor-canvas'),
            canvasContent: document.querySelector('.block-editor-canvas-content'),
            settings: document.querySelector('.block-editor-settings'),
            form: document.querySelector('.block-editor-form'),
            toggleButtons: document.querySelectorAll('[data-toggle]'),
            settingsTabs: document.querySelectorAll('.block-editor-settings-tab'),
        };
    }

    /**
     * Configura event listeners
     */
    setupEventListeners() {
        // Toggle sidebar buttons
        this.elements.toggleButtons.forEach(btn => {
            btn.addEventListener('click', (e) => this.toggleSidebar(e));
        });

        // Settings tabs
        this.elements.settingsTabs.forEach(tab => {
            tab.addEventListener('click', (e) => this.switchSettingsTab(e));
        });

        // Auto-save quando o formulário mudar
        if (this.elements.form) {
            this.elements.form.addEventListener('change', () => {
                this.debounceAutoSave();
            });
        }
    }

    /**
     * Carrega a lista de blocos disponíveis
     */
    loadBlockInserts() {
        // Aqui virão os blocos do servidor via data attribute ou API
        const blockTypes = [
            { id: 'paragraph', label: 'Parágrafo', icon: '📝', desc: 'Texto normal' },
            { id: 'heading', label: 'Título', icon: '📖', desc: 'Cabeçalho' },
            { id: 'image', label: 'Imagem', icon: '🖼️', desc: 'Inserir imagem' },
            { id: 'video', label: 'Vídeo', icon: '🎬', desc: 'Incorporar vídeo' },
            { id: 'code', label: 'Código', icon: '💻', desc: 'Bloco de código' },
            { id: 'quote', label: 'Citação', icon: '💬', desc: 'Blockquote' },
            { id: 'alert', label: 'Alerta', icon: '⚠️', desc: 'Caixa de alerta' },
            { id: 'divider', label: 'Divisor', icon: '─', desc: 'Linha divisória' },
        ];

        this.elements.inserterList.innerHTML = blockTypes
            .map(block => `
                <div class="block-editor-inserter-item" draggable="true" data-block-type="${block.id}">
                    <div>${block.icon}</div>
                    <div class="block-editor-inserter-label">
                        <div class="block-editor-inserter-label-title">${block.label}</div>
                        <div class="block-editor-inserter-label-desc">${block.desc}</div>
                    </div>
                </div>
            `)
            .join('');

        // Setup drag events
        document.querySelectorAll('.block-editor-inserter-item').forEach(item => {
            item.addEventListener('dragstart', (e) => this.handleBlockDragStart(e));
        });
    }

    /**
     * Toggle sidebar (inserter ou settings)
     */
    toggleSidebar(e) {
        const target = e.currentTarget.dataset.toggle;
        
        if (target === 'inserter') {
            this.elements.inserter.classList.toggle('visible');
        } else if (target === 'settings') {
            this.elements.settings.classList.toggle('visible');
        }
    }

    /**
     * Switch between settings tabs
     */
    switchSettingsTab(e) {
        const tabName = e.currentTarget.dataset.tab;
        
        // Remover active de todas as tabs
        this.elements.settingsTabs.forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Remover active de todos os contents
        document.querySelectorAll('.block-editor-settings-tab-content').forEach(content => {
            content.style.display = 'none';
        });
        
        // Marcar como active
        e.currentTarget.classList.add('active');
        const tabContent = document.querySelector(`#settings-${tabName}-tab`);
        if (tabContent) {
            tabContent.style.display = 'block';
        }
    }

    /**
     * Handle block drag start
     */
    handleBlockDragStart(e) {
        const blockType = e.currentTarget.dataset.blockType;
        e.dataTransfer.effectAllowed = 'copy';
        e.dataTransfer.setData('text/plain', blockType);
        console.log(`[Lafily] Arrastando bloco: ${blockType}`);
    }

    /**
     * Debounce auto-save
     */
    debounceAutoSave() {
        if (!this.state.settings.autosave) return;
        
        clearTimeout(this.timers.autoSave);
        this.timers.autoSave = setTimeout(() => {
            console.log('[Lafily] Auto-saving...');
            // O Livewire já cuida do save automático
        }, this.state.settings.debounce);
    }

    /**
     * Setup responsivo
     */
    setupResponsive() {
        window.addEventListener('resize', () => {
            this.updateLayout();
        });
    }

    /**
     * Update layout based on screen size
     */
    updateLayout() {
        const width = window.innerWidth;
        
        if (width <= 1024) {
            // Mobile/tablet: esconder sidebars por padrão
            this.elements.inserter.classList.remove('visible');
            this.elements.settings.classList.remove('visible');
        }
    }
}

// Export global
window.LafilyBlockEditor = new LafilyBlockEditor();
