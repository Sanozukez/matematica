{{-- plataforma/resources/views/filament/resources/lesson-resource/pages/lesson-editor-styles.blade.php --}}
@php
$css = file_get_contents(resource_path('css/lesson-editor.css'));
@endphp
{!! $css !!}

