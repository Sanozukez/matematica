{{-- plataforma/resources/views/filament/resources/lesson-resource/pages/edit-lesson-fullscreen.blade.php --}}
@php
    $record = $this->getRecord();
@endphp

<x-filament-panels::page>
    @once
        @push('styles')
        <style>
            .fi-sidebar,
            .fi-topbar,
            .fi-header,
            .fi-breadcrumbs {
                display: none !important;
            }

            .fi-main,
            .fi-main-ctn,
            main.fi-main {
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
            }

            .fi-no {
                inset: 24px 24px auto auto !important;
                max-width: calc(100vw - 48px);
                width: 320px;
                pointer-events: none;
                z-index: 30;
            }

            .gutenberg-editor-wrapper {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                padding-top: 80px;
                background: #f3f4f6;
            }

            .fullscreen-layout {
                flex: 1;
                display: grid;
                grid-template-columns: minmax(0, 1fr) 320px;
                gap: 24px;
                padding: 24px;
            }

            .editor-column {
                min-width: 0;
            }

            .editor-content-section {
                background: #fff;
                border-radius: 12px;
                padding: 24px;
                box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
            }

            .sidebar-column {
                min-width: 0;
                background: #fff;
                border-radius: 12px;
                padding: 24px;
                box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
                height: fit-content;
                position: sticky;
                top: 96px;
                align-self: start;
            }

            @media (max-width: 1024px) {
                .fullscreen-layout {
                    grid-template-columns: minmax(0, 1fr);
                }

                .sidebar-column {
                    position: relative;
                    top: auto;
                    margin-top: 24px;
                }
            }
        </style>

        <style>
            @include('filament.resources.lesson-resource.pages.lesson-editor-styles')
        </style>
        @endpush
    @endonce

    <div class="gutenberg-editor-wrapper" wire:key="editor-wrapper">
        <div class="gutenberg-top-bar">
            <div class="gutenberg-top-bar-left">
                <div class="gutenberg-brand">
                    <span>🧮</span>
                    <span>Matemática</span>
                </div>

                <a href="{{ $this->getResource()::getUrl('index') }}" class="gutenberg-back-btn">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Voltar</span>
                </a>

                <div class="gutenberg-block-toolbar" id="gutenberg-block-toolbar"></div>
            </div>

            <div class="gutenberg-top-bar-right">
                <button
                    wire:click="save"
                    type="button"
                    class="btn-save flex items-center justify-center gap-2"
                >
                    Salvar alterações
                </button>
            </div>
        </div>

        <div class="fullscreen-layout">
            <div class="editor-column">
                <div class="editor-content-section">
                    <x-filament-panels::form wire:submit="save" class="lesson-form">
                        {{ $this->form }}

                        <button type="submit" class="sr-only">
                            Salvar
                        </button>
                    </x-filament-panels::form>
                </div>
            </div>

            <div class="sidebar-column">
                <div class="space-y-3">
                    <button
                        wire:click="save"
                        type="button"
                        class="btn-save w-full flex items-center justify-center gap-2"
                    >
                        Salvar alterações
                    </button>

                    <a
                        href="{{ $this->getResource()::getUrl('index') }}"
                        class="btn-cancel w-full flex items-center justify-center gap-2"
                    >
                        Cancelar
                    </a>
                </div>

                <div class="border-t border-gray-100 pt-6 mt-6">
                    <h3 class="font-semibold text-gray-900 mb-4 text-sm uppercase tracking-wider">
                        Publicação
                    </h3>

                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">
                            Permalink
                        </label>

                        <div class="text-sm text-gray-500 font-mono bg-gray-50 p-2 rounded border border-gray-200 break-all">
                            /lessons/{{ $record->slug }}
                        </div>
                    </div>

                    <div class="space-y-4 bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Status</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $record->is_active ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $record->is_active ? 'Publicado' : 'Rascunho' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Criado em</span>
                            <span class="text-gray-900">{{ optional($record->created_at)->format('d/m/Y') }}</span>
                        </div>

                        <div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="checkbox"
                                    wire:model="data.is_active"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                >
                                <span class="text-sm text-gray-700">Ativo/Visível</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6 mt-6 space-y-4">
                    <h3 class="font-semibold text-gray-900 text-sm uppercase tracking-wider">
                        Atributos
                    </h3>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Módulo</label>
                        <div class="text-sm text-gray-900 bg-gray-50 p-2 rounded border border-gray-200">
                            {{ $record->module->title }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Duração (min)</label>
                        <input
                            type="number"
                            wire:model="data.duration_minutes"
                            class="w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Ordem</label>
                        <input
                            type="number"
                            wire:model="data.order"
                            class="w-full rounded-md border-gray-300 shadow-sm sm:text-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

@once
    @push('scripts')
        <script>
            @include('filament.resources.lesson-resource.pages.lesson-editor-scripts')
        </script>
    @endpush
@endonce
