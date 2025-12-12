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
            inserterOpen: false,
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
            layout: document.querySelector('#block-editor-layout'),
            inserter: document.querySelector('.block-editor-inserter'),
            inserterList: document.querySelector('#inserter-list'),
            canvas: document.querySelector('.block-editor-canvas'),
            canvasContent: document.querySelector('.block-editor-canvas-content'),
            settings: document.querySelector('.block-editor-settings'),
            form: document.querySelector('.block-editor-form'),
            toggleButtons: document.querySelectorAll('[data-toggle]'),
            settingsTabs: document.querySelectorAll('.block-editor-settings-tab'),
            themeToggle: document.querySelector('#theme-toggle'),
            userMenu: document.querySelector('.block-editor-user'),
            userMenuButtons: document.querySelectorAll('.block-editor-user-menu-btn'),
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

        // Theme toggle disabled in Lafily (delegated to platform)

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
            { id: 'paragraph', label: 'Parágrafo', icon: 'paragraph', desc: 'Texto normal' },
            { id: 'heading', label: 'Título', icon: 'heading', desc: 'Cabeçalho' },
            { id: 'image', label: 'Imagem', icon: 'image', desc: 'Inserir imagem' },
            { id: 'video', label: 'Vídeo', icon: 'video', desc: 'Incorporar vídeo' },
            { id: 'code', label: 'Código', icon: 'code', desc: 'Bloco de código' },
            { id: 'quote', label: 'Citação', icon: 'quote', desc: 'Blockquote' },
            { id: 'alert', label: 'Alerta', icon: 'alert', desc: 'Caixa de alerta' },
            { id: 'divider', label: 'Divisor', icon: 'divider', desc: 'Linha divisória' },
        ];

        this.elements.inserterList.innerHTML = blockTypes
            .map(block => `
                <div class="block-editor-inserter-item" draggable="true" data-block-type="${block.id}">
                    <div class="block-editor-inserter-icon">${this.renderIcon(block.icon)}</div>
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

    renderIcon(name) {
        const icons = {
            paragraph: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.75h9.75M4.5 9.75h9.75m-9.75 6h9.75m-9.75-3h9.75M18 6.75v10.5" /></svg>',
            heading: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5v-15m0 7.5h15m0 0v-7.5m0 7.5v7.5" /></svg>',
            image: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5v13.5H3.75z"/><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 12.75 10.5 9l3 4.5 1.5-1.5 1.5 1.5"/><circle cx="9" cy="8" r="1" /></svg>',
            video: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-9A2.25 2.25 0 0 0 2.25 5.25v13.5A2.25 2.25 0 0 0 4.5 21h9a2.25 2.25 0 0 0 2.25-2.25V15l6 3.75V5.25L15.75 9Z" /></svg>',
            code: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9 5.25 12 9 15m6-6 3.75 3L15 15" /></svg>',
            quote: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h-3v6h4.5v-3h-1.5zm9 0h-3v6h4.5v-3h-1.5z" /></svg>',
            alert: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12Zm9-3.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>',
            divider: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5" /></svg>',
        };
        return icons[name] || '';
    }

    /**
     * Toggle sidebar (inserter ou settings)
     */
    toggleSidebar(e) {
        const target = e.currentTarget.dataset.toggle;
        
        if (target === 'inserter') {
            this.state.inserterOpen = !this.state.inserterOpen;
            if (this.elements.layout) {
                this.elements.layout.classList.toggle('collapsed-inserter', !this.state.inserterOpen);
            }
            this.elements.inserter.classList.toggle('visible', this.state.inserterOpen);

            // Atualiza estado visual do botão
            const btn = e.currentTarget;
            btn.setAttribute('aria-expanded', this.state.inserterOpen ? 'true' : 'false');
            btn.classList.toggle('is-open', this.state.inserterOpen);
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
     * Inicializa estado dos tabs (primeiro ativo)
     */
    setupTabs() {
        const tabs = this.elements.settingsTabs;
        if (!tabs || tabs.length === 0) return;

        // Marca primeiro como ativo
        tabs.forEach(tab => tab.classList.remove('active'));
        const contents = document.querySelectorAll('.block-editor-settings-tab-content');
        contents.forEach(c => c.style.display = 'none');

        const first = tabs[0];
        const firstContent = document.querySelector(`#settings-${first.dataset.tab}-tab`);
        if (first) first.classList.add('active');
        if (firstContent) firstContent.style.display = 'block';
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

    /**
     * Inicializa estado dos tabs (primeiro ativo)
     */
    setupTabs() {
        const tabs = this.elements.settingsTabs;
        if (!tabs || tabs.length === 0) return;

        // Marca primeiro como ativo
        tabs.forEach(tab => tab.classList.remove('active'));
        const contents = document.querySelectorAll('.block-editor-settings-tab-content');
        contents.forEach(c => c.style.display = 'none');

        const first = tabs[0];
        const firstContent = document.querySelector(`#settings-${first?.dataset?.tab}-tab`);
        if (first) first.classList.add('active');
        if (firstContent) firstContent.style.display = 'block';
    }
}

// Export global
window.LafilyBlockEditor = new LafilyBlockEditor();
