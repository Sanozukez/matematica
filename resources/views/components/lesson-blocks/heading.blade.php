{{-- plataforma/resources/views/components/lesson-blocks/heading.blade.php --}}
@php
    $tag = $level ?? 'h2';
    $colorStyle = isset($color) && $color ? "color: {$color};" : '';
@endphp

<{{ $tag }} class="lesson-heading font-bold mb-4" style="{{ $colorStyle }}">
    {{ $content }}
</{{ $tag }}>

<style>
    .lesson-heading.h2 { font-size: 2rem; margin-top: 2rem; }
    .lesson-heading.h3 { font-size: 1.5rem; margin-top: 1.5rem; }
    .lesson-heading.h4 { font-size: 1.25rem; margin-top: 1rem; }
</style>

