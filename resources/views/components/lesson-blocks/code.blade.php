{{-- plataforma/resources/views/components/lesson-blocks/code.blade.php --}}
<div class="lesson-code my-6">
    @if(isset($caption) && $caption)
        <div class="code-caption bg-gray-800 text-gray-300 px-4 py-2 rounded-t-lg text-sm font-mono">
            {{ $caption }}
        </div>
    @endif
    
    <pre class="bg-gray-900 text-gray-100 p-4 {{ isset($caption) ? 'rounded-b-lg' : 'rounded-lg' }} overflow-x-auto"><code class="language-{{ $language ?? 'plaintext' }}">{{ $content }}</code></pre>
</div>

{{-- Incluir Highlight.js para syntax highlighting --}}
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script>hljs.highlightAll();</script>
@endpush

