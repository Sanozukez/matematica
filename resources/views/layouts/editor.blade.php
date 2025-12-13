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
        
        {{-- Alpine.js CDN (fallback if not loaded by Filament) --}}
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body>
        <x-editor-navbar />
        
        {{ $slot }}
    </body>
</html>
