{{-- plataforma/resources/views/components/lesson-blocks/quote.blade.php --}}
<blockquote class="lesson-quote border-l-4 border-blue-500 bg-blue-50 p-6 my-6 rounded-r-lg">
    <p class="text-lg text-gray-800 italic mb-3">
        "{{ $content }}"
    </p>
    
    @if(isset($author) && $author)
        <footer class="text-sm text-gray-600">
            <cite class="font-semibold not-italic">— {{ $author }}</cite>
            @if(isset($source) && $source)
                <span class="text-gray-500">, {{ $source }}</span>
            @endif
        </footer>
    @endif
</blockquote>

