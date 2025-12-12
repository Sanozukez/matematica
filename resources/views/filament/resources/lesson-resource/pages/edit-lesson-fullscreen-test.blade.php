<x-filament-panels::page>
    <style>
        /* FORÇAR VISIBILIDADE TOTAL - DEBUG */
        * {
            opacity: 1 !important;
            visibility: visible !important;
        }
        
        form, form * {
            color: #000 !important;
            background: #fff !important;
            display: block !important;
        }
        
        input, textarea, select {
            border: 2px solid #f00 !important;
            padding: 10px !important;
            display: block !important;
        }
        
        .fi-fo-builder,
        .fi-fo-builder-item,
        .fi-fo-field-wrp {
            border: 2px dashed blue !important;
            padding: 10px !important;
            margin: 10px 0 !important;
            background: #f0f0f0 !important;
            display: block !important;
        }
        
        /* BLOQUEAR QUALQUER JS QUE TENTE ESCONDER ELEMENTOS */
        [style*="display: none"],
        [style*="visibility: hidden"],
        [style*="opacity: 0"] {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
    </style>
    
    <h1 style="color: red; font-size: 24px; font-weight: bold; display: block !important;">TEST VIEW - DEBUG MODE</h1>
    
    <div style="border: 2px solid red; padding: 20px; margin: 20px 0; background: yellow; display: block !important;">
        <h2 style="color: black; font-size: 18px;">Debug Info:</h2>
        <p style="color: black;">Form Object Type: {{ get_class($this->form) }}</p>
        <p style="color: black;">Form has components: {{ $this->form->getComponents() ? 'YES' : 'NO' }}</p>
        <p style="color: black;">Components count: {{ count($this->form->getComponents()) }}</p>
    </div>
    
    <div style="border: 2px solid blue; padding: 20px; background: white; display: block !important;">
        <h2 style="color: black; font-size: 18px;">Form Render Attempt:</h2>
        <form wire:submit="save" style="display: block !important;">
            @foreach ($this->form->getComponents() as $component)
                <div style="border: 2px dashed green; margin: 10px 0; padding: 10px; background: #e0ffe0; display: block !important;">
                    <p style="color: black; font-weight: bold;">Component: {{ get_class($component) }}</p>
                    {{ $component }}
                </div>
            @endforeach
            
            <button type="submit" style="background: green; color: white; padding: 15px 30px; font-size: 16px; border: none; cursor: pointer; display: block !important;">Salvar Teste</button>
        </form>
    </div>
    
    <script>
        // BLOQUEAR O LESSON-EDITOR.JS
        console.log('[DEBUG] Bloqueando lesson-editor.js...');
        
        // Prevenir que qualquer script esconda elementos
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                    const target = mutation.target;
                    if (target.style.display === 'none' || 
                        target.style.visibility === 'hidden' || 
                        target.style.opacity === '0') {
                        console.warn('[DEBUG] Script tentou esconder elemento:', target);
                        target.style.display = 'block';
                        target.style.visibility = 'visible';
                        target.style.opacity = '1';
                    }
                }
            });
        });
        
        // Observar mudanças em todo o documento
        observer.observe(document.body, {
            attributes: true,
            attributeFilter: ['style'],
            subtree: true
        });
        
        console.log('[DEBUG] Observer ativado - elementos não podem ser escondidos');
    </script>
</x-filament-panels::page>
