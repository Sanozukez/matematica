{{-- plataforma/resources/views/components/lesson-blocks/divider.blade.php --}}
@php
    $hrClass = match($style ?? 'solid') {
        'solid' => 'border-solid border-gray-300',
        'dashed' => 'border-dashed border-gray-400',
        'dotted' => 'border-dotted border-gray-400',
        'thick' => 'border-solid border-gray-800 border-t-4',
        'space' => 'border-none',
        default => 'border-solid border-gray-300'
    };
    
    $spacing = $style === 'space' ? 'my-12' : 'my-8';
@endphp

<hr class="lesson-divider {{ $hrClass }} {{ $spacing }}">

