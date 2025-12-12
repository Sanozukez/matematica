{{-- plataforma/resources/views/filament/resources/lesson-resource/pages/lesson-editor-scripts.blade.php --}}
@php
$js = file_get_contents(resource_path('js/lesson-editor.js'));
@endphp
{!! $js !!}
