{{-- Lafily Block Editor Layout --}}
@php
    $record = $this->getRecord();
@endphp

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/block-editor/css/block-editor.css') }}">
@endpush
    <div class="block-editor-wrapper">
        {{-- TOP BAR --}}
        <header class="block-editor-topbar">
            <div class="block-editor-topbar-left">
                {{-- Toggle Sidebar Button --}}
                <button 
                    class="block-editor-toggle-sidebar" 
                    data-toggle="inserter"
                    title="Mostrar/ocultar blocos"
                    aria-label="Toggle block inserter"
                >
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                {{-- Brand --}}
                <div class="block-editor-brand">
                    <span class="block-editor-brand-icon">🧱</span>
                    <span>Lafily</span>
                </div>

                {{-- Back Button --}}
                <a href="{{ $this->getResource()::getUrl('index') }}" class="block-editor-back-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Voltar</span>
                </a>

                {{-- Toolbar (para ferramentas de bloco selecionado) --}}
                <div class="block-editor-toolbar" id="block-toolbar"></div>
            </div>

            <div class="block-editor-topbar-right">
                {{-- Menu do usuário (simplificado) --}}
                <div style="display: flex; align-items: center; gap: 12px;">
                    @auth
                        <span style="font-size: 12px; color: #6b7280;">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button 
                                type="submit" 
                                style="padding: 8px 12px; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 6px; cursor: pointer; font-size: 12px; color: #6b7280;"
                            >
                                Sair
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </header>

        {{-- MAIN LAYOUT (3 COLUNAS) --}}
        <div class="block-editor-layout">
            {{-- LEFT SIDEBAR: BLOCK INSERTER --}}
            <aside class="block-editor-inserter" id="block-inserter">
                <div class="block-editor-inserter-header">
                    Blocos
                </div>
                <div class="block-editor-inserter-list" id="inserter-list">
                    {{-- Blocos serão populados aqui via JS --}}
                </div>
            </aside>

            {{-- CENTER: CANVAS / EDITOR --}}
            <main class="block-editor-canvas">
                <div class="block-editor-canvas-content">
                    <div class="block-editor-canvas-wrapper">
                        {{-- Renderizar formulário do Filament aqui --}}
                        <x-filament-panels::form wire:submit="save" class="block-editor-form">
                            {{ $this->form }}
                            <button type="submit" class="block-editor-sr-only">
                                Salvar
                            </button>
                        </x-filament-panels::form>
                    </div>
                </div>
            </main>

            {{-- RIGHT SIDEBAR: BLOCK SETTINGS --}}
            <aside class="block-editor-settings" id="block-settings">
                <div class="block-editor-settings-tabs">
                    <button class="block-editor-settings-tab active" data-tab="block">Bloco</button>
                    <button class="block-editor-settings-tab" data-tab="page">Lição</button>
                </div>

                <div class="block-editor-settings-content">
                    {{-- Block Settings Tab --}}
                    <div id="settings-block-tab" class="block-editor-settings-tab-content">
                        <div class="block-editor-settings-empty">
                            <div class="block-editor-settings-empty-icon">📋</div>
                            <div class="block-editor-settings-empty-text">
                                Selecione um bloco para editar suas propriedades
                            </div>
                        </div>
                    </div>

                    {{-- Page Settings Tab --}}
                    <div id="settings-page-tab" class="block-editor-settings-tab-content" style="display: none;">
                        <div class="block-editor-settings-section">
                            <div class="block-editor-settings-section-title">Status</div>
                            <div class="block-editor-settings-section-content">
                                <div class="block-editor-settings-info">
                                    <strong>Título:</strong> {{ $record->title }}<br>
                                    <strong>Slug:</strong> {{ $record->slug }}<br>
                                    <strong>Módulo:</strong> {{ optional($record->module)->title ?? '-' }}<br>
                                    <strong>Criado:</strong> {{ optional($record->created_at)->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>

                        <div class="block-editor-settings-section">
                            <div class="block-editor-settings-section-title">Configurações</div>
                            <div class="block-editor-settings-section-content">
                                <div class="block-editor-settings-field">
                                    <label>Duração (minutos)</label>
                                    <input 
                                        type="number" 
                                        wire:model="data.duration_minutes"
                                        min="1"
                                        value="{{ $record->duration_minutes ?? 5 }}"
                                    >
                                </div>
                                <div class="block-editor-settings-field">
                                    <label>Ordem</label>
                                    <input 
                                        type="number" 
                                        wire:model="data.order"
                                        value="{{ $record->order ?? 0 }}"
                                    >
                                </div>
                                <div class="block-editor-settings-field">
                                    <label>
                                        <input type="checkbox" wire:model="data.is_active">
                                        Ativa/Visível
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    {{-- Lafily JS --}}
    <script src="{{ asset('vendor/block-editor/js/block-editor.js') }}" defer></script>
    <script>
        // Inicializar o editor quando a página carregar
        document.addEventListener('DOMContentLoaded', function() {
            if (window.LafilyBlockEditor) {
                window.LafilyBlockEditor.init();
            }
        });
    </script>
@endpush
