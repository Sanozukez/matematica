<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ theme: localStorage.getItem('theme') || 'system' }" :class="{ 'dark': theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches) }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Matemática') }} - Editor de Lição</title>

        {{-- Filament CSS --}}
        @filamentStyles

        {{-- Navbar Isolation CSS (protege do CSS do editor) --}}
        <link rel="stylesheet" href="{{ asset('css/navbar-isolation.css') }}">

        {{-- Custom Editor Styles --}}
        <style>
            /* Remove Filament Layout Components */
            [data-slot="sidebar"],
            .fi-sidebar,
            .fi-sidebar-header,
            .fi-sidebar-footer,
            [class*="sidebar"],
            nav[class*="sidebar"] {
                display: none !important;
            }

            /* Make main content full width (no sidebar space) */
            html:not(.dark) .fi-page,
            html.dark .fi-page {
                margin-left: 0 !important;
            }

            /* Adjust body for full editor */
            body {
                overflow-y: auto;
            }
        </style>

        @stack('styles')
    </head>
    <body class="antialiased">
            {{-- Custom platform navbar for fullscreen pages --}}
            <x-platform-navbar />

        {{-- Livewire Components --}}
            {{-- Page content injected by Filament below navbar --}}
            {{ $slot }}

        {{-- Livewire Scripts only (avoid full Filament JS on editor) --}}
        @livewireScripts

        {{-- Alpine JS & Theme Switcher --}}
        <script>
            // Theme Management
            document.addEventListener('theme-changed', (e) => {
                const theme = e.detail;
                localStorage.setItem('theme', theme);
                
                // Update Alpine data
                Alpine.store('theme', theme);
                
                // Update HTML class
                const html = document.documentElement;
                if (theme === 'dark') {
                    html.classList.add('dark');
                } else if (theme === 'light') {
                    html.classList.remove('dark');
                } else {
                    // System preference
                    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        html.classList.add('dark');
                    } else {
                        html.classList.remove('dark');
                    }
                }
            });

            // Observar mudanças no sistema
            window.matchMedia('(prefers-color-scheme: dark)').addListener((e) => {
                const theme = localStorage.getItem('theme') || 'system';
                if (theme === 'system') {
                    if (e.matches) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            });
        </script>

        @stack('scripts')
    </body>
</html>
