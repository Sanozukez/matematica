{{-- plataforma/resources/views/components/lesson-blocks/latex.blade.php --}}
<div class="lesson-latex my-8">
    <div class="latex-formula bg-gray-50 border border-gray-200 rounded-lg p-6 {{ ($display_mode ?? true) ? 'text-center' : '' }}">
        <div class="katex-render">
            {{-- Renderizado pelo KaTeX no JavaScript --}}
            <span class="katex-source" data-latex="{{ $content }}" data-display="{{ ($display_mode ?? true) ? 'true' : 'false' }}">
                {{ $content }}
            </span>
        </div>
    </div>
    
    @if(isset($caption) && $caption)
        <p class="text-center text-sm text-gray-600 mt-3 italic">
            {{ $caption }}
        </p>
    @endif
</div>

{{-- Incluir KaTeX --}}
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
@endpush

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.katex-source').forEach(function(element) {
        const latex = element.getAttribute('data-latex');
        const displayMode = element.getAttribute('data-display') === 'true';
        
        try {
            const rendered = katex.renderToString(latex, {
                displayMode: displayMode,
                throwOnError: false,
                errorColor: '#cc0000',
                strict: false
            });
            element.innerHTML = rendered;
        } catch (e) {
            element.innerHTML = '<span class="text-red-600">Erro ao renderizar: ' + e.message + '</span>';
        }
    });
});
</script>
@endpush

