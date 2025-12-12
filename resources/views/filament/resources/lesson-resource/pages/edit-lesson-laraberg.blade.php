{{-- plataforma/resources/views/filament/resources/lesson-resource/pages/edit-lesson-laraberg.blade.php --}}

<x-filament-panels::page>
    @push('styles')
    <!-- Laraberg CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/laraberg/css/laraberg.css') }}">
    <style>
        /* Esconder sidebar completamente */
        .fi-sidebar,
        [data-sidebar],
        aside[class*="sidebar"],
        .fi-main-sidebar,
        nav[class*="sidebar"],
        [role="navigation"][class*="sidebar"] {
            display: none !important;
            width: 0 !important;
            min-width: 0 !important;
            max-width: 0 !important;
            visibility: hidden !important;
        }
        
        /* Ajustar conte do principal para ocupar toda largura com padding igual */
        .fi-main,
        .fi-body,
        main[class*="main"],
        [class*="main-content"],
        .fi-main-content {
            margin-left: 0 !important;
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        
        /* Garantir padding igual no container da p gina */
        .fi-page,
        [class*="page-content"],
        .fi-page-content {
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }
        
        /* Header/Topbar sempre vis vel e expandido - ocupar toda largura SEM padding */
        .fi-header,
        header[class*="header"],
        [class*="topbar"],
        .fi-topbar,
        nav[class*="topbar"],
        .fi-topbar > nav,
        .fi-topbar > div {
            width: 100% !important;
            left: 0 !important;
            margin-left: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        
        /* Container interno do topbar tamb m sem padding */
        .fi-topbar > div,
        .fi-topbar nav > div,
        [class*="topbar"] > div {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
        
        /* Garantir que o brand name sempre apare a e n o colapse */
        .fi-brand,
        [class*="brand"],
        [class*="logo"],
        .fi-brand-name,
        [class*="brand-name"],
        .fi-brand-text,
        a[class*="brand"],
        .fi-topbar .fi-brand,
        .fi-topbar a[class*="brand"] {
            min-width: fit-content !important;
            width: auto !important;
            flex-shrink: 0 !important;
            white-space: nowrap !important;
            overflow: visible !important;
            text-overflow: clip !important;
            display: flex !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
        
        /* Garantir que o texto do brand apare a */
        .fi-brand-name,
        .fi-brand-text,
        .fi-brand-name span,
        .fi-brand-text span,
        [class*="brand"] span,
        .fi-topbar .fi-brand-name {
            display: inline !important;
            visibility: visible !important;
            opacity: 1 !important;
            color: inherit !important;
        }
        
        /* For ar brand name a aparecer mesmo quando colapsado */
        .fi-brand-name[style*="display: none"],
        .fi-brand-text[style*="display: none"] {
            display: inline !important;
        }
        
        /* Esconder bot o de colapsar sidebar */
        .fi-sidebar-collapse-button,
        [class*="sidebar-collapse"],
        button[aria-label*="collapse"],
        button[aria-label*="sidebar"],
        [data-sidebar-toggle] {
            display: none !important;
        }
        
        /* Estilos customizados para o editor */
        .laraberg-editor-wrapper {
            min-height: 600px;
        }
        
        /* Adicionar cone voltar no nav (ao lado do brand) */
        .fi-topbar .fi-brand::before {
            content: '';
            display: inline-flex;
            align-items: center;
            margin-right: 0.75rem;
        }
        
        /* Estilos para o bot o voltar no header */
        .laraberg-back-icon {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            width: 2rem !important;
            height: 2rem !important;
            margin-right: 0.75rem !important;
            color: #6b7280 !important;
            text-decoration: none;
            border-radius: 0.375rem;
            transition: all 0.2s;
            padding: 0 !important;
        }
        
        .laraberg-back-icon:hover {
            color: #1f2937 !important;
            background-color: #f3f4f6 !important;
        }
        
        .laraberg-back-icon svg {
            width: 1.25rem;
            height: 1.25rem;
        }
        
        /* Garantir que o bot o voltar apare a antes do brand */
        .fi-topbar .fi-header-actions {
            order: -1;
        }
        
        .fi-topbar .laraberg-back-icon {
            order: -2;
        }
        
        /* Esconder t tulo da p gina */
        .fi-page-header-heading,
        [class*="page-header-heading"],
        h1[class*="heading"],
        .fi-page-heading {
            display: none !important;
        }
    </style>
    @endpush

    <!-- Container do Editor -->
    <div class="laraberg-editor-wrapper">
        <!-- Formul rio do Filament (inclui t tulo e editor) -->
        {{ $this->form }}
    </div>
    
    @push('scripts')
    <!-- Depend ncias do Gutenberg -->
    <script src="https://unpkg.com/react@17.0.2/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@17.0.2/umd/react-dom.production.min.js"></script>
    <script src="https://unpkg.com/moment/min/moment.min.js"></script>
    <script src="https://unpkg.com/jquery/dist/jquery.min.js"></script>
    
    <!-- Laraberg JS -->
    <script src="{{ asset('vendor/laraberg/js/laraberg.js') }}"></script>
    
    <script>
        // N O suprimir erros - mostrar todos
        console.log('[Laraberg] Script carregado - erros N O ser o suprimidos');
        
        // Log de erros globais
        window.addEventListener('error', function(e) {
            console.error('[Laraberg] Erro global capturado:', {
                message: e.message,
                filename: e.filename,
                lineno: e.lineno,
                colno: e.colno,
                error: e.error,
                stack: e.error?.stack
            });
        });
        
        // Log de promises rejeitadas
        window.addEventListener('unhandledrejection', function(e) {
            console.error('[Laraberg] Promise rejeitada:', {
                reason: e.reason,
                promise: e.promise
            });
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('[Laraberg] DOMContentLoaded disparado');
            
            // Garantir que o brand name apare a
            function ensureBrandVisible() {
                const brand = document.querySelector('.fi-brand, [class*="brand"]');
                const brandName = document.querySelector('.fi-brand-name, [class*="brand-name"]');
                const brandLink = document.querySelector('.fi-brand a, [class*="brand"] a');
                
                if (brand) {
                    brand.style.display = 'flex';
                    brand.style.visibility = 'visible';
                    brand.style.opacity = '1';
                }
                
                if (brandName) {
                    brandName.style.display = 'inline';
                    brandName.style.visibility = 'visible';
                    brandName.style.opacity = '1';
                    brandName.style.color = 'inherit';
                    // Remover qualquer classe que esconda
                    brandName.classList.remove('hidden', 'sr-only');
                }
                
                // Garantir que o link do brand tamb m apare a
                if (brandLink) {
                    const brandText = brandLink.querySelector('span, .fi-brand-name');
                    if (brandText) {
                        brandText.style.display = 'inline';
                        brandText.style.visibility = 'visible';
                        brandText.style.opacity = '1';
                    }
                }
            }
            
            ensureBrandVisible();
            setTimeout(ensureBrandVisible, 500);
            setTimeout(ensureBrandVisible, 1000);
            
            // Observar mudan as no DOM (Livewire pode atualizar)
            const observer = new MutationObserver(ensureBrandVisible);
            observer.observe(document.body, { childList: true, subtree: true });
            
            // Reinicializar Laraberg ap s save do Livewire
            Livewire.hook('morph.updated', ({ el, component }) => {
                // Aguardar um pouco para garantir que o DOM foi atualizado
                setTimeout(() => {
                    ensureBrandVisible();
                    
                    // Verificar se o Laraberg precisa ser reinicializado
                    const textarea = document.querySelector('textarea[id^="laraberg-"]');
                    if (textarea && typeof Laraberg !== 'undefined') {
                        const editorId = textarea.id;
                        // Verificar se o editor j foi inicializado
                        if (!textarea.hasAttribute('data-laraberg-initialized')) {
                            // Reinicializar apenas se necess rio
                            console.log('Reinicializando Laraberg ap s atualiza  o do Livewire');
                        }
                    }
                }, 100);
            });
            
            // Configurar blocos permitidos ap s Laraberg carregar
            // Aguardar wp estar dispon vel
            const checkWP = setInterval(() => {
                if (typeof wp !== 'undefined' && wp.blocks) {
                    clearInterval(checkWP);
                    
                    // Remover todos os embeds exceto YouTube
                    const blockTypes = wp.blocks.getBlockTypes();
                    blockTypes.forEach(block => {
                        if (block.name && block.name.startsWith('core/embed') && block.name !== 'core/embed/youtube') {
                            try {
                                wp.blocks.unregisterBlockType(block.name);
                                console.log('Bloco removido:', block.name);
                            } catch (e) {
                                console.warn('Erro ao remover bloco:', block.name, e);
                            }
                        }
                    });
                    
                    console.log('Laraberg configurado - apenas YouTube permitido');
                }
            }, 100);
        });
    </script>
    @endpush
</x-filament-panels::page>
