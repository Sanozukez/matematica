{{-- plataforma/resources/views/components/lesson-content.blade.php --}}
{{-- 
    Componente para renderizar o conteúdo completo de uma lição
    Uso: <x-lesson-content :blocks="$lesson->content" />
--}}

@props(['blocks'])

<div class="lesson-content max-w-4xl mx-auto py-8">
    @if(is_array($blocks) && count($blocks) > 0)
        @foreach($blocks as $block)
            @php
                $type = $block['type'] ?? 'paragraph';
                $data = $block['data'] ?? [];
            @endphp
            
            @switch($type)
                @case('paragraph')
                    <x-lesson-blocks.paragraph :content="$data['content'] ?? ''" />
                    @break
                
                @case('heading')
                    <x-lesson-blocks.heading 
                        :level="$data['level'] ?? 'h2'"
                        :content="$data['content'] ?? ''"
                        :color="$data['color'] ?? null"
                    />
                    @break
                
                @case('image')
                    <x-lesson-blocks.image 
                        :file="$data['file'] ?? ''"
                        :alt="$data['alt'] ?? ''"
                        :caption="$data['caption'] ?? null"
                        :alignment="$data['alignment'] ?? 'center'"
                    />
                    @break
                
                @case('list')
                    <x-lesson-blocks.list 
                        :style="$data['style'] ?? 'bullet'"
                        :content="$data['content'] ?? ''"
                        :items="$data['items'] ?? null"
                    />
                    @break
                
                @case('quote')
                    <x-lesson-blocks.quote 
                        :content="$data['content'] ?? ''"
                        :author="$data['author'] ?? null"
                        :source="$data['source'] ?? null"
                    />
                    @break
                
                @case('code')
                    <x-lesson-blocks.code 
                        :content="$data['content'] ?? ''"
                        :language="$data['language'] ?? 'plaintext'"
                        :caption="$data['caption'] ?? null"
                    />
                    @break
                
                @case('alert')
                    <x-lesson-blocks.alert 
                        :type="$data['type'] ?? 'info'"
                        :title="$data['title'] ?? null"
                        :content="$data['content'] ?? ''"
                    />
                    @break
                
                @case('video')
                    <x-lesson-blocks.video 
                        :provider="$data['provider'] ?? 'youtube'"
                        :url="$data['url'] ?? ''"
                        :caption="$data['caption'] ?? null"
                    />
                    @break
                
                @case('latex')
                    <x-lesson-blocks.latex 
                        :content="$data['content'] ?? ''"
                        :display_mode="$data['display_mode'] ?? true"
                        :caption="$data['caption'] ?? null"
                    />
                    @break
                
                @case('divider')
                    <x-lesson-blocks.divider 
                        :style="$data['style'] ?? 'solid'"
                    />
                    @break
                
                @case('table')
                    <x-lesson-blocks.table 
                        :caption="$data['caption'] ?? null"
                        :headers="$data['headers'] ?? []"
                        :rows="$data['rows'] ?? []"
                    />
                    @break
                
                @default
                    <div class="bg-yellow-50 border border-yellow-300 rounded p-4 my-4">
                        <p class="text-yellow-800">
                            ⚠️ Tipo de bloco desconhecido: <code>{{ $type }}</code>
                        </p>
                    </div>
            @endswitch
        @endforeach
    @else
        <div class="text-center text-gray-500 py-12">
            <p class="text-lg">📄 Nenhum conteúdo disponível</p>
        </div>
    @endif
</div>

{{-- Estilos globais --}}
<style>
    .lesson-content {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        line-height: 1.6;
        color: #1f2937;
    }
    
    .lesson-block {
        margin-bottom: 1.5rem;
    }
    
    .prose {
        color: #374151;
    }
    
    .prose p {
        margin-bottom: 1rem;
    }
    
    .prose a {
        color: #3b82f6;
        text-decoration: underline;
    }
    
    .prose strong {
        font-weight: 700;
        color: #111827;
    }
    
    