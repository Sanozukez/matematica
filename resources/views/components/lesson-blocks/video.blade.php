{{-- plataforma/resources/views/components/lesson-blocks/video.blade.php --}}
@php
    // Extrair ID do vídeo conforme provedor
    $embedUrl = null;
    
    if ($provider === 'youtube') {
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
        $videoId = $matches[1] ?? null;
        if ($videoId) {
            $embedUrl = "https://www.youtube.com/embed/{$videoId}";
        }
    } elseif ($provider === 'vimeo') {
        preg_match('/vimeo\.com\/(\d+)/', $url, $matches);
        $videoId = $matches[1] ?? null;
        if ($videoId) {
            $embedUrl = "https://player.vimeo.com/video/{$videoId}";
        }
    } elseif ($provider === 'bunny') {
        $embedUrl = $url; // Bunny.net já fornece URL de embed
    }
@endphp

<div class="lesson-video my-8">
    @if($embedUrl)
        <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden shadow-lg">
            <iframe 
                src="{{ $embedUrl }}" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen
                class="w-full h-full"
                style="aspect-ratio: 16/9; min-height: 400px;"
            ></iframe>
        </div>
    @else
        <div class="bg-gray-100 border border-gray-300 rounded-lg p-6 text-center">
            <p class="text-gray-600">Erro ao carregar vídeo. URL inválida.</p>
            <a href="{{ $url }}" target="_blank" class="text-blue-600 hover:underline">
                Abrir link: {{ $url }}
            </a>
        </div>
    @endif
    
    @if(isset($caption) && $caption)
        <div class="mt-4 text-sm text-gray-600 bg-gray-50 p-3 rounded">
            {{ $caption }}
        </div>
    @endif
</div>

