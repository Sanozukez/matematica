<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Matemática') }} - Editor de Lição</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { 
                font-family: system-ui, -apple-system, sans-serif; 
                background: #ffffff;
                color: #000000;
            }
        </style>
        
        @stack('styles')

        {{-- Block Editor CSS --}}
        <link rel="stylesheet" href="{{ asset('vendor/block-editor/css/layout.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/block-editor/css/block-editor.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/block-editor/css/toolbar.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/block-editor/css/topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/block-editor/css/canvas.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/block-editor/css/menus.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/block-editor/css/sidebar-left.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/block-editor/css/sidebar-right.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/block-editor/css/sidebar-enhanced.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/block-editor/css/block-inserter-between.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/block-editor/css/blocks.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/block-editor/css/states.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/block-editor/css/responsive.css') }}">

        {{-- Block Editor JS (order matters) --}}
        <script defer src="{{ asset('vendor/block-editor/js/block-types.js') }}"></script>
        <script defer src="{{ asset('vendor/block-editor/js/modules/BlockManager.js') }}"></script>
        <script defer src="{{ asset('vendor/block-editor/js/modules/HistoryManager.js') }}"></script>
        <script defer src="{{ asset('vendor/block-editor/js/modules/EventHandlers.js') }}"></script>
        <script defer src="{{ asset('vendor/block-editor/js/modules/DragDropManager.js') }}"></script>
        <script defer src="{{ asset('vendor/block-editor/js/modules/FormatManager.js') }}"></script>
        <script defer src="{{ asset('vendor/block-editor/js/modules/SlashCommands.js') }}"></script>
        <script defer src="{{ asset('vendor/block-editor/js/modules/StateManager.js') }}"></script>
        <script defer src="{{ asset('vendor/block-editor/js/modules/BlockRenderers.js') }}"></script>
        <script defer src="{{ asset('vendor/block-editor/js/BlockEditorCore.js') }}"></script>

        @stack('scripts')
    </head>
    <body>
        <x-editor-navbar />

        {{ $slot }}
    </body>
</html>
