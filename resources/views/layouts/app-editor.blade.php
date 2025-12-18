<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Block Editor assets (carregados para o editor fullscreen) --}}
    <link rel="stylesheet" href="{{ asset('vendor/block-editor/css/block-editor.css') }}">
    <script src="{{ asset('vendor/block-editor/js/block-types.js') }}"></script>
    <script src="{{ asset('vendor/block-editor/js/modules/BlockManager.js') }}"></script>
    <script src="{{ asset('vendor/block-editor/js/modules/EventHandlers.js') }}"></script>
    <script src="{{ asset('vendor/block-editor/js/modules/DragDropManager.js') }}"></script>
    <script src="{{ asset('vendor/block-editor/js/modules/FormatManager.js') }}"></script>
    <script src="{{ asset('vendor/block-editor/js/modules/BlockRenderers.js') }}"></script>
    <script src="{{ asset('vendor/block-editor/js/modules/StateManager.js') }}"></script>
    <script src="{{ asset('vendor/block-editor/js/modules/SlashCommands.js') }}"></script>
    <script src="{{ asset('vendor/block-editor/js/modules/BlockTransform.js') }}"></script>
    <script src="{{ asset('vendor/block-editor/js/BlockEditorCore.js') }}"></script>
</head>

<body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-950">
    <main class="w-full min-h-screen">
        {{ $slot }}
    </main>
</body>
</html>