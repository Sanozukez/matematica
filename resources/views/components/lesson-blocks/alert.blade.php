{{-- plataforma/resources/views/components/lesson-blocks/alert.blade.php --}}
@php
    $styles = [
        'info' => [
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-500',
            'text' => 'text-blue-900',
            'icon' => 'ℹ️'
        ],
        'success' => [
            'bg' => 'bg-green-50',
            'border' => 'border-green-500',
            'text' => 'text-green-900',
            'icon' => '✅'
        ],
        'warning' => [
            'bg' => 'bg-yellow-50',
            'border' => 'border-yellow-500',
            'text' => 'text-yellow-900',
            'icon' => '⚠️'
        ],
        'danger' => [
            'bg' => 'bg-red-50',
            'border' => 'border-red-500',
            'text' => 'text-red-900',
            'icon' => '⛔'
        ],
    ];
    
    $style = $styles[$type ?? 'info'];
@endphp

<div class="lesson-alert {{ $style['bg'] }} border-l-4 {{ $style['border'] }} p-5 my-6 rounded-r-lg">
    <div class="flex items-start gap-3">
        <div class="text-2xl flex-shrink-0">{{ $style['icon'] }}</div>
        <div class="flex-1">
            @if(isset($title) && $title)
                <h4 class="{{ $style['text'] }} font-bold mb-2">{{ $title }}</h4>
            @endif
            <p class="{{ $style['text'] }}">{{ $content }}</p>
        </div>
    </div>
</div>

