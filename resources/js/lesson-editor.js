// plataforma/resources/js/lesson-editor.js

/**
 * Gerenciador do Editor Fullscreen de Li��es
 * 
 * Responsabilidades:
 * - Esconder sidebar e header do Filament
 * - Estilizar bot�o "Adicionar Bloco"
 * - Mover toolbars do TipTap para a barra fixa superior
 * - Gerenciar sele��o de blocos e exibi��o de propriedades
 */

(function() {
    'use strict';

    const LessonEditor = {
        // Elementos da barra superior
        brand: null,
        blockToolbar: null,

        /**
         * Inicializa o editor
         */
        init() {
            if (!document.querySelector('.gutenberg-editor-wrapper')) {
                return; // N�o est� na p�gina do editor
            }

            this.hideFilamentUI();
            this.initElements();
            this.styleAddButton();
            this.setupBlockSelection();
            this.adjustProseMirrorHeight();
            this.setupTitleSlugSync();
            this.fixInsertBetweenMenu();
            this.setupAddBlockMenu();
            this.setupObservers();
        },

        /**
         * Sincroniza slug quando t�tulo mudar
         */
        setupTitleSlugSync() {
            const titleInput = document.querySelector('.lesson-title-input input[wire\\:model*="title"]');
            if (titleInput) {
                titleInput.addEventListener('blur', () => {
                    // Livewire j� atualiza o slug automaticamente via afterStateUpdated
                    // Mas garantimos que o evento seja disparado
                    titleInput.dispatchEvent(new Event('input', { bubbles: true }));
                });
            }
        },

        /**
         * Esconde sidebar e header do Filament
         */
        hideFilamentUI() {
            const sidebar = document.querySelector('.fi-sidebar');
            if (sidebar) sidebar.style.display = 'none';

            const header = document.querySelector('.fi-header');
            if (header) header.style.display = 'none';

            const mainContent = document.querySelector('.fi-main-content');
            if (mainContent) {
                mainContent.style.marginLeft = '0';
                mainContent.style.width = '100%';
                mainContent.style.paddingTop = '0';
            }
        },

        /**
         * Inicializa refer�ncias aos elementos
         */
        initElements() {
            this.brand = document.getElementById('gutenberg-brand');
            this.blockToolbar = document.getElementById('gutenberg-block-toolbar');
        },

        /**
         * Estiliza bot�o "Adicionar Bloco" estilo WordPress (quadrado preto com plus)
         */
        styleAddButton() {
            // M�ltiplos seletores para garantir que pegue todos os casos
            const selectors = [
                '[data-filament-builder-add-button]',
                '.fi-fo-builder-add-action button',
                '.fi-fo-builder-add-action > button',
                '.fi-fo-builder-add-action > div > button',
                'button[type="button"][x-on\\:click*="addBlock"]',
                'button[x-on\\:click*="addBlock"]',
                '.fi-fo-builder-add-action'
            ];

            const addButtons = document.querySelectorAll(selectors.join(', '));

            addButtons.forEach(btn => {
                // Aplicar estilos diretamente
                btn.style.setProperty('background', '#000', 'important');
                btn.style.setProperty('color', '#fff', 'important');
                btn.style.setProperty('border', 'none', 'important');
                btn.style.setProperty('width', '36px', 'important');
                btn.style.setProperty('height', '36px', 'important');
                btn.style.setProperty('min-width', '36px', 'important');
                btn.style.setProperty('min-height', '36px', 'important');
                btn.style.setProperty('max-width', '36px', 'important');
                btn.style.setProperty('max-height', '36px', 'important');
                btn.style.setProperty('border-radius', '2px', 'important');
                btn.style.setProperty('display', 'flex', 'important');
                btn.style.setProperty('align-items', 'center', 'important');
                btn.style.setProperty('justify-content', 'center', 'important');
                btn.style.setProperty('font-size', '20px', 'important');
                btn.style.setProperty('font-weight', '300', 'important');
                btn.style.setProperty('line-height', '1', 'important');
                btn.style.setProperty('margin', '1rem auto', 'important');
                btn.style.setProperty('padding', '0', 'important');
                btn.style.setProperty('box-shadow', 'none', 'important');
                btn.style.setProperty('transition', 'background-color 0.1s ease', 'important');
                btn.style.setProperty('cursor', 'pointer', 'important');

                // Adicionar classe para facilitar sele��o CSS
                btn.classList.add('fi-fo-builder-add-button');

                // REMOVER COMPLETAMENTE todo o texto e conte�do, exceto SVG
                // Primeiro, remover todos os spans e labels
                const allTextElements = btn.querySelectorAll('.fi-btn-label, span, div:not([class*="icon"]):not([class*="svg"])');
                allTextElements.forEach(el => {
                    // Verificar se n�o � um SVG ou container de SVG
                    if (!el.querySelector('svg') && !el.closest('svg')) {
                        el.remove();
                    }
                });

                // Remover texto direto no bot�o
                Array.from(btn.childNodes).forEach(child => {
                    if (child.nodeType === Node.TEXT_NODE) {
                        child.remove();
                    } else if (child.nodeType === Node.ELEMENT_NODE) {
                        // Se for um elemento que cont�m apenas texto (sem SVG)
                        if (child.tagName !== 'SVG' && !child.querySelector('svg') && child.textContent.trim() && !child.classList.contains('sr-only')) {
                            child.remove();
                        }
                    }
                });

                // Remover qualquer SVG existente que n�o seja nosso plus icon
                const existingSvgs = btn.querySelectorAll('svg:not([data-plus-icon])');
                existingSvgs.forEach(svg => svg.remove());
                
                // SEMPRE criar/recriar �cone SVG de plus
                const existingPlusIcon = btn.querySelector('svg[data-plus-icon]');
                if (existingPlusIcon) {
                    existingPlusIcon.remove();
                }
                
                // Criar SVG de plus (estilo WordPress)
                const plusIcon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                plusIcon.setAttribute('data-plus-icon', 'true');
                plusIcon.setAttribute('width', '20');
                plusIcon.setAttribute('height', '20');
                plusIcon.setAttribute('viewBox', '0 0 20 20');
                plusIcon.setAttribute('fill', 'none');
                plusIcon.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
                plusIcon.style.cssText = 'color: #fff !important; stroke: #fff !important; width: 20px !important; height: 20px !important; flex-shrink: 0 !important; display: block !important;';
                
                // Linha horizontal
                const line1 = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                line1.setAttribute('x1', '4');
                line1.setAttribute('y1', '10');
                line1.setAttribute('x2', '16');
                line1.setAttribute('y2', '10');
                line1.setAttribute('stroke', '#fff');
                line1.setAttribute('stroke-width', '2');
                line1.setAttribute('stroke-linecap', 'round');
                
                // Linha vertical
                const line2 = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                line2.setAttribute('x1', '10');
                line2.setAttribute('y1', '4');
                line2.setAttribute('x2', '10');
                line2.setAttribute('y2', '16');
                line2.setAttribute('stroke', '#fff');
                line2.setAttribute('stroke-width', '2');
                line2.setAttribute('stroke-linecap', 'round');
                
                plusIcon.appendChild(line1);
                plusIcon.appendChild(line2);
                
                // Limpar bot�o completamente e adicionar apenas o SVG
                btn.innerHTML = '';
                btn.appendChild(plusIcon);

                // Adicionar hover effect
                btn.addEventListener('mouseenter', () => {
                    btn.style.setProperty('background', '#1a1a1a', 'important');
                });
                btn.addEventListener('mouseleave', () => {
                    btn.style.setProperty('background', '#000', 'important');
                });
            });
        },

        /**
         * Configura detec��o de sele��o de blocos
         */
        setupBlockSelection() {
            // Detectar cliques em blocos
            document.addEventListener('click', (e) => {
                // Verificar se e.target � um elemento DOM v�lido
                if (!e || !e.target || typeof e.target.closest !== 'function') {
                    return;
                }

                const block = e.target.closest('.fi-fo-builder-item');

                if (block) {
                    // Bloco clicado
                    document.querySelectorAll('.fi-fo-builder-item').forEach(b => {
                        b.classList.remove('is-selected');
                    });
                    block.classList.add('is-selected');

                    // Obter tipo do bloco
                    const blockLabel = block.querySelector('.fi-fo-builder-item-header')?.textContent?.trim() || '';
                    const blockType = blockLabel.toLowerCase();

                    // Mostrar toolbar do bloco
                    if (this.brand) this.brand.style.display = 'none';
                    if (this.blockToolbar) {
                        this.blockToolbar.classList.add('active');
                        // Aguardar um pouco para garantir que a toolbar do TipTap foi renderizada
                        setTimeout(() => {
                            this.updateBlockToolbar(blockType, block);
                        }, 100);
                    }
                } else {
                    // Verificar novamente se e.target � v�lido antes de usar closest
                    const isInTopBar = e.target && typeof e.target.closest === 'function' && e.target.closest('.gutenberg-top-bar');
                    const isInProseMirror = e.target && typeof e.target.closest === 'function' && e.target.closest('.ProseMirror');
                    
                    if (!isInTopBar && !isInProseMirror) {
                        // Clicou fora, deselecionar tudo
                        document.querySelectorAll('.fi-fo-builder-item').forEach(b => {
                            b.classList.remove('is-selected');
                        });
                        
                        // N�O restaurar toolbars originais - elas devem permanecer escondidas
                        // Apenas limpar a toolbar da barra fixa
                        
                        if (this.brand) this.brand.style.display = 'flex';
                        if (this.blockToolbar) {
                            this.blockToolbar.classList.remove('active');
                            this.blockToolbar.classList.remove('has-tiptap-toolbar');
                            this.blockToolbar.innerHTML = '';
                        }
                    }
                }
            });
        },

        /**
         * Atualiza toolbar de propriedades do bloco
         * Move a toolbar do TipTap do bloco para a barra fixa superior
         */
        updateBlockToolbar(blockType, blockElement) {
            if (!this.blockToolbar || !blockElement) return;

            // Limpar toolbar anterior
            this.blockToolbar.innerHTML = '';

            // Procurar toolbar do TipTap dentro do bloco (v�rios seletores poss�veis)
            let tiptapToolbar = blockElement.querySelector(
                '.tiptap-toolbar, ' +
                '[data-tiptap-toolbar], ' +
                '[role="toolbar"], ' +
                '.tiptap-editor [role="toolbar"], ' +
                '.fi-fo-field-wrp [role="toolbar"], ' +
                '.fi-fo-field-wrp .tiptap-toolbar, ' +
                '.fi-fo-field-wrp [data-tiptap-toolbar], ' +
                '[wire\\:id] [role="toolbar"], ' +
                '[x-data*="tiptap"] [role="toolbar"]'
            );

            // Se n�o encontrou, procurar em qualquer container dentro do bloco
            if (!tiptapToolbar) {
                const containers = blockElement.querySelectorAll('[class*="tiptap"], [class*="editor"], .fi-fo-field-wrp');
                for (const container of containers) {
                    tiptapToolbar = container.querySelector('[role="toolbar"], .tiptap-toolbar, [data-tiptap-toolbar]');
                    if (tiptapToolbar) break;
                }
            }

            if (tiptapToolbar) {
                // N�O adicionar label - toolbar direta sem label
                // Clonar todos os elementos da toolbar original, mas evitar elementos com Alpine.js
                Array.from(tiptapToolbar.children).forEach(child => {
                    // Verificar se o elemento ou seus filhos t�m express�es Alpine.js problem�ticas
                    const hasProblematicAlpine = (element) => {
                        // Verificar se o elemento tem x-data (contexto Alpine.js)
                        if (element.hasAttribute('x-data')) {
                            return true;
                        }
                        
                        // Verificar todos os atributos Alpine.js
                        const alpineAttrs = ['x-bind', 'x-on', 'x-show', 'x-if', 'x-for', 'x-text', 'x-html', 
                                           'x-model', 'x-cloak', 'x-init', 'x-effect', 'x-ignore', 
                                           'x-ref', 'x-id', 'x-teleport', 'x-transition'];
                        
                        for (const attr of alpineAttrs) {
                            if (element.hasAttribute(attr) || element.hasAttribute(attr + ':class') || 
                                element.hasAttribute(attr + ':click') || element.hasAttribute(attr + ':show')) {
                                const value = element.getAttribute(attr) || 
                                            element.getAttribute(attr + ':class') || 
                                            element.getAttribute(attr + ':click') || 
                                            element.getAttribute(attr + ':show') || '';
                                
                                // Verificar se cont�m express�es problem�ticas
                                const problematicExpressions = [
                                    'editor()',
                                    'editor().',
                                    'fullScreenMode',
                                    'indicator()',
                                    'updatedAt',
                                    'isActive',
                                    'isFocused',
                                    'getAttributes'
                                ];
                                
                                if (problematicExpressions.some(expr => value.includes(expr))) {
                                    return true;
                                }
                            }
                        }
                        
                        // Verificar atributos com : (bindings)
                        Array.from(element.attributes).forEach(attr => {
                            if (attr.name.startsWith(':') || attr.name.startsWith('@')) {
                                const problematicExpressions = [
                                    'editor()',
                                    'editor().',
                                    'fullScreenMode',
                                    'indicator()',
                                    'updatedAt'
                                ];
                                if (problematicExpressions.some(expr => attr.value.includes(expr))) {
                                    return true;
                                }
                            }
                        });
                        
                        return false;
                    };
                    
                    // Verificar elemento e seus filhos
                    const elementHasProblem = hasProblematicAlpine(child) || 
                        Array.from(child.querySelectorAll('*')).some(el => hasProblematicAlpine(el));
                    
                    // Se tiver express�es Alpine.js problem�ticas, criar wrapper simples
                    if (elementHasProblem) {
                        // Criar um bot�o simples que delega para o original
                        const wrapper = document.createElement('button');
                        wrapper.type = 'button';
                        wrapper.className = child.className.replace(/is-active|bg-gray-500\/30|hidden/g, '').trim();
                        
                        // Copiar apenas o conte�do visual (SVG, �cones) sem elementos Alpine.js
                        const visualContent = child.cloneNode(true);
                        this.removeAllAlpineAttributes(visualContent);
                        wrapper.innerHTML = visualContent.innerHTML;
                        
                        // Copiar atributos visuais importantes
                        if (child.hasAttribute('x-tooltip')) {
                            const tooltip = child.getAttribute('x-tooltip');
                            wrapper.setAttribute('title', tooltip.replace(/'/g, '').replace(/"/g, ''));
                        }
                        
                        // Copiar aria-label se existir
                        if (child.hasAttribute('aria-label')) {
                            wrapper.setAttribute('aria-label', child.getAttribute('aria-label'));
                        }
                        
                        // Desabilitar Alpine.js neste elemento
                        wrapper.setAttribute('x-ignore', 'true');
                        wrapper.setAttribute('data-alpine-ignore', 'true');
                        
                        wrapper.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            if (child && typeof child.click === 'function') {
                                child.click();
                            }
                            // Atualizar estado visual ap�s um delay
                            setTimeout(() => {
                                this.updateButtonStates(wrapper, child);
                            }, 50);
                        });
                        
                        // Sincronizar estado inicial
                        this.updateButtonStates(wrapper, child);
                        this.blockToolbar.appendChild(wrapper);
                    } else {
                        const cloned = child.cloneNode(true);
                        // Remover TODOS os atributos Alpine.js recursivamente
                        this.removeAllAlpineAttributes(cloned);
                        // Preservar eventos e funcionalidades
                        this.preserveToolbarEvents(cloned, child);
                        this.blockToolbar.appendChild(cloned);
                    }
                });

                // Esconder toolbar original do bloco PERMANENTEMENTE
                tiptapToolbar.style.display = 'none';
                tiptapToolbar.dataset.movedToTopbar = 'true';
                // Adicionar classe para garantir que nunca apare�a
                tiptapToolbar.classList.add('gutenberg-hidden-toolbar');

                // Adicionar classe para estiliza��o
                this.blockToolbar.classList.add('has-tiptap-toolbar');
            }
            // Se n�o encontrar toolbar, n�o mostrar nada (toolbar vazia)
        },

        /**
         * Remove TODOS os atributos Alpine.js de um elemento e seus filhos recursivamente
         */
        removeAllAlpineAttributes(element) {
            if (!element) return;
            
            // Lista de atributos Alpine.js a remover
            const alpineAttrs = [
                'x-data', 'x-bind', 'x-on', 'x-show', 'x-if', 'x-for', 'x-text', 'x-html',
                'x-model', 'x-cloak', 'x-init', 'x-effect', 'x-ignore', 'x-ref', 'x-id',
                'x-teleport', 'x-transition', 'x-tooltip', 'x-bind:class', 'x-bind:style',
                'x-on:click', 'x-on:mouseenter', 'x-on:mouseleave', 'x-on:focus', 'x-on:blur',
                ':class', ':style', '@click', '@mouseenter', '@mouseleave', '@focus', '@blur',
                'wire:id', 'wire:key', 'wire:model', 'wire:click', 'wire:loading'
            ];
            
            // Remover atributos do elemento atual
            Array.from(element.attributes).forEach(attr => {
                // Remover qualquer atributo que comece com x-, @, :, ou wire:
                if (attr.name.startsWith('x-') || 
                    attr.name.startsWith('@') || 
                    attr.name.startsWith(':') ||
                    attr.name.startsWith('wire:') ||
                    alpineAttrs.includes(attr.name)) {
                    element.removeAttribute(attr.name);
                }
            });
            
            // Adicionar atributos para desabilitar Alpine.js
            element.setAttribute('x-ignore', 'true');
            element.setAttribute('data-alpine-ignore', 'true');
            
            // Remover recursivamente de todos os filhos
            Array.from(element.querySelectorAll('*')).forEach(child => {
                Array.from(child.attributes).forEach(attr => {
                    if (attr.name.startsWith('x-') || 
                        attr.name.startsWith('@') || 
                        attr.name.startsWith(':') ||
                        attr.name.startsWith('wire:') ||
                        alpineAttrs.includes(attr.name)) {
                        child.removeAttribute(attr.name);
                    }
                });
                child.setAttribute('x-ignore', 'true');
                child.setAttribute('data-alpine-ignore', 'true');
            });
        },

        /**
         * Preserva eventos da toolbar original usando delega��o
         */
        preserveToolbarEvents(clonedElement, originalElement) {
            // Copiar todos os atributos
            Array.from(originalElement.attributes).forEach(attr => {
                if (attr.name !== 'style' && attr.name !== 'data-moved-to-topbar') {
                    clonedElement.setAttribute(attr.name, attr.value);
                }
            });

            // Para cada bot�o, delegar evento para o original
            const clonedButtons = clonedElement.querySelectorAll('button, [role="button"], [type="button"]');
            const originalButtons = originalElement.querySelectorAll('button, [role="button"], [type="button"]');
            
            clonedButtons.forEach((clonedBtn, index) => {
                if (originalButtons[index]) {
                    const origBtn = originalButtons[index];
                    
                    // Copiar atributos do bot�o original
                    Array.from(origBtn.attributes).forEach(attr => {
                        if (attr.name !== 'style') {
                            clonedBtn.setAttribute(attr.name, attr.value);
                        }
                    });
                    
                    // Delegar clique para o bot�o original
                    clonedBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        origBtn.click();
                        // Atualizar estado visual
                        setTimeout(() => this.updateButtonStates(clonedBtn, origBtn), 10);
                    });
                    
                    // Sincronizar estado inicial
                    this.updateButtonStates(clonedBtn, origBtn);
                }
            });
        },

        /**
         * Sincroniza estado visual dos bot�es (ativo/inativo)
         */
        updateButtonStates(clonedBtn, origBtn) {
            if (origBtn.classList.contains('is-active')) {
                clonedBtn.classList.add('is-active');
            } else {
                clonedBtn.classList.remove('is-active');
            }
        },

        /**
         * Retorna label formatado do tipo de bloco
         */
        getBlockLabel(blockType) {
            if (blockType.includes('par�grafo') || blockType.includes('paragraph')) {
                return 'Par�grafo';
            } else if (blockType.includes('t�tulo') || blockType.includes('heading')) {
                return 'T�tulo';
            } else if (blockType.includes('imagem') || blockType.includes('image')) {
                return 'Imagem';
            } else if (blockType.includes('lista') || blockType.includes('list')) {
                return 'Lista';
            } else if (blockType.includes('cita��o') || blockType.includes('quote')) {
                return 'Cita��o';
            } else if (blockType.includes('c�digo') || blockType.includes('code')) {
                return 'C�digo';
            } else {
                return blockType || 'Bloco';
            }
        },

        /**
         * Ajusta altura autom�tica do ProseMirror
         */
        adjustProseMirrorHeight() {
            const proseMirrors = document.querySelectorAll('.editor-column .ProseMirror');
            proseMirrors.forEach(editor => {
                // Remover flex e garantir display block
                editor.style.display = 'block';
                editor.style.flex = 'none';
                editor.style.flexGrow = 'none';
                editor.style.flexShrink = 'none';
                editor.style.flexBasis = 'auto';
                
                // Remover altura fixa
                editor.style.height = 'auto';
                editor.style.minHeight = 'auto';
                editor.style.maxHeight = 'none';
                
                // Ajustar altura baseada no conte�do
                const updateHeight = () => {
                    // Garantir que n�o est� com flex
                    if (getComputedStyle(editor).display === 'flex') {
                        editor.style.display = 'block';
                    }
                    
                    editor.style.height = 'auto';
                    const scrollHeight = editor.scrollHeight;
                    if (scrollHeight > 0) {
                        editor.style.height = scrollHeight + 'px';
                    } else {
                        // Altura m�nima para uma linha
                        const lineHeight = parseFloat(getComputedStyle(editor).lineHeight) || 1.75;
                        const fontSize = parseFloat(getComputedStyle(editor).fontSize) || 16;
                        editor.style.height = (lineHeight * fontSize) + 'px';
                    }
                };
                
                // Atualizar ao digitar
                editor.addEventListener('input', updateHeight);
                editor.addEventListener('keyup', updateHeight);
                editor.addEventListener('paste', () => setTimeout(updateHeight, 10));
                
                // Atualizar inicialmente e ap�s mudan�as do Livewire
                setTimeout(updateHeight, 100);
                
                // Observar mudan�as no conte�do
                const contentObserver = new MutationObserver(updateHeight);
                contentObserver.observe(editor, {
                    childList: true,
                    subtree: true,
                    characterData: true
                });
            });
        },

        /**
         * Corrige menu "Insert Between Blocks" para n�o fechar ao mover mouse
         */
        fixInsertBetweenMenu() {
            // Usar delega��o de eventos para capturar todos os menus, incluindo os criados dinamicamente
            document.addEventListener('mouseenter', (e) => {
                const button = e.target.closest('.fi-fo-builder-item-insert button, .fi-fo-builder-item-insert [role="button"], [data-filament-builder-insert] button, .fi-fo-builder-insert-action button');
                if (!button) return;

                const menuWrapper = button.closest('.fi-fo-builder-item-insert, [data-filament-builder-insert], .fi-fo-builder-insert-action');
                if (!menuWrapper) return;

                // Encontrar dropdown
                const findDropdown = () => {
                    return menuWrapper.querySelector('[role="menu"], .fi-dropdown-panel, [x-show], .fi-dropdown-list');
                };

                // For�ar menu a abrir e manter aberto
                const openAndKeepOpen = () => {
                    const dropdown = findDropdown();
                    if (dropdown) {
                        // Remover qualquer atributo x-show que possa estar fechando
                        if (dropdown.hasAttribute('x-show')) {
                            dropdown.setAttribute('x-show', 'true');
                        }
                        
                        // For�ar estilos
                        dropdown.style.setProperty('display', 'block', 'important');
                        dropdown.style.setProperty('opacity', '1', 'important');
                        dropdown.style.setProperty('visibility', 'visible', 'important');
                        dropdown.style.setProperty('pointer-events', 'auto', 'important');
                        dropdown.style.setProperty('z-index', '9999', 'important');
                    }
                };

                // Abrir imediatamente
                openAndKeepOpen();

                // Observar mudan�as no dropdown
                const observer = new MutationObserver(() => {
                    openAndKeepOpen();
                });

                observer.observe(menuWrapper, {
                    childList: true,
                    subtree: true,
                    attributes: true,
                    attributeFilter: ['style', 'x-show', 'class']
                });

                // Manter aberto quando mouse est� sobre o wrapper ou dropdown
                const keepOpen = () => {
                    openAndKeepOpen();
                };

                menuWrapper.addEventListener('mouseenter', keepOpen);
                button.addEventListener('mouseenter', keepOpen);

                // Fechar quando sair do menu (sem delay)
                const closeMenu = () => {
                    const dropdown = findDropdown();
                    if (dropdown) {
                        // Remover estilos for�ados e fechar
                        dropdown.style.removeProperty('display');
                        dropdown.style.removeProperty('opacity');
                        dropdown.style.removeProperty('visibility');
                        dropdown.style.removeProperty('pointer-events');
                        // Remover atributo x-show se existir
                        if (dropdown.hasAttribute('x-show')) {
                            dropdown.setAttribute('x-show', 'false');
                        }
                        observer.disconnect();
                    }
                };

                // Fechar quando sair do wrapper
                menuWrapper.addEventListener('mouseleave', (e) => {
                    const relatedTarget = e.relatedTarget;
                    const dropdown = findDropdown();
                    
                    // Fechar se saiu do wrapper e n�o est� indo para o dropdown
                    if (!menuWrapper.contains(relatedTarget) && 
                        (!dropdown || !dropdown.contains(relatedTarget))) {
                        closeMenu();
                    }
                });

                // Fechar quando sair do dropdown tamb�m
                const setupDropdownClose = () => {
                    const dropdown = findDropdown();
                    if (dropdown) {
                        dropdown.addEventListener('mouseleave', (e) => {
                            const relatedTarget = e.relatedTarget;
                            // Fechar se saiu do dropdown e n�o est� indo para o wrapper
                            if (!dropdown.contains(relatedTarget) && 
                                !menuWrapper.contains(relatedTarget)) {
                                closeMenu();
                            }
                        });
                    }
                };

                // Verificar dropdown periodicamente
                const checkInterval = setInterval(() => {
                    setupDropdownClose();
                }, 100);

                setTimeout(() => clearInterval(checkInterval), 2000);
            }, true); // Usar capture phase para pegar antes do Alpine.js
        },

        /**
         * Configura menu "Adicionar Bloco" para fechar ao sair do hover
         */
        setupAddBlockMenu() {
            // Usar delega��o de eventos para capturar o bot�o de adicionar bloco
            document.addEventListener('mouseleave', (e) => {
                // Verificar se e.target � um elemento DOM v�lido
                if (!e || !e.target || typeof e.target.closest !== 'function') {
                    return;
                }

                const button = e.target.closest('[data-filament-builder-add-button], .fi-fo-builder-add-action button');
                if (!button) return;

                const menuWrapper = button.closest('.fi-fo-builder-add-action, [data-filament-builder-add-button]');
                if (!menuWrapper) return;

                // Encontrar dropdown
                const findDropdown = () => {
                    return menuWrapper.querySelector('[role="menu"], .fi-dropdown-panel, [x-show], .fi-dropdown-list');
                };

                // Fechar quando sair do wrapper
                const relatedTarget = e.relatedTarget;
                const dropdown = findDropdown();
                
                // Fechar se saiu do wrapper e n�o est� indo para o dropdown
                if (!menuWrapper.contains(relatedTarget) && 
                    (!dropdown || !dropdown.contains(relatedTarget))) {
                    if (dropdown) {
                        dropdown.style.removeProperty('display');
                        dropdown.style.removeProperty('opacity');
                        dropdown.style.removeProperty('visibility');
                        if (dropdown.hasAttribute('x-show')) {
                            dropdown.setAttribute('x-show', 'false');
                        }
                    }
                }
            }, true);
        },

        /**
         * Configura observadores para mudan�as no DOM (Livewire)
         */
        setupObservers() {
            const observer = new MutationObserver(() => {
                this.styleAddButton();
                this.adjustProseMirrorHeight();
                this.fixInsertBetweenMenu();
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });

            // Re-executar quando Livewire atualizar
            document.addEventListener('livewire:load', () => {
                this.init();
            });

            document.addEventListener('livewire:update', () => {
                setTimeout(() => {
                    this.styleAddButton();
                    this.initElements();
                    this.adjustProseMirrorHeight();
                    // Re-sincronizar estados dos bot�es se houver toolbar ativa
                    if (this.blockToolbar && this.blockToolbar.classList.contains('active')) {
                        const activeBlock = document.querySelector('.fi-fo-builder-item.is-selected');
                        if (activeBlock) {
                            this.updateBlockToolbar('', activeBlock);
                        }
                    }
                }, 100);
            });
        }
    };

    // Inicializar quando DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => LessonEditor.init());
    } else {
        LessonEditor.init();
    }

    // Re-inicializar ap�s Livewire carregar
    document.addEventListener('livewire:load', () => {
        setTimeout(() => LessonEditor.init(), 500);
    });

    // Suprimir erros do Alpine.js relacionados a vari�veis n�o definidas em elementos clonados
    const originalConsoleError = console.error;
    console.error = function(...args) {
        // Filtrar erros relacionados a vari�veis n�o definidas em elementos clonados
        const errorMessage = args.join(' ');
        
        // Lista expandida de erros a suprimir
        const suppressedErrors = [
            'editor is not defined',
            'editor().',
            'fullScreenMode is not defined',
            'indicator is not defined',
            'indicator().',
            'updatedAt is not defined',
            'isActive',
            'isFocused',
            'getAttributes'
        ];
        
        // Verificar se � um erro do Alpine.js relacionado a vari�veis n�o definidas
        const isAlpineError = errorMessage.includes('Alpine Expression Error') || 
                             errorMessage.includes('ReferenceError') ||
                             errorMessage.includes('Alpine') ||
                             errorMessage.includes('[Alpine]');
        
        const isSuppressedError = isAlpineError && suppressedErrors.some(error => 
            errorMessage.includes(error)
        );
        
        if (isSuppressedError) {
            // Suprimir este erro espec�fico
            return;
        }
        // Para todos os outros erros, usar o console.error original
        originalConsoleError.apply(console, args);
    };

    // Suprimir erros do Livewire/Alpine.js relacionados a vari�veis n�o definidas
    window.addEventListener('error', (e) => {
        const suppressedErrors = [
            'editor is not defined',
            'editor().',
            'fullScreenMode is not defined',
            'indicator is not defined',
            'indicator().',
            'updatedAt is not defined',
            'isActive',
            'isFocused',
            'getAttributes'
        ];
        
        const isAlpineError = e.message && (
            e.message.includes('Alpine') ||
            e.message.includes('[Alpine]') ||
            e.filename?.includes('livewire') ||
            e.filename?.includes('alpine')
        );
        
        if (isAlpineError && suppressedErrors.some(error => e.message.includes(error))) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    }, true);

    // Suprimir erros de TypeError relacionados a closest
    window.addEventListener('error', (e) => {
        if (e.message && e.message.includes('closest is not a function')) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    }, true);
})();

