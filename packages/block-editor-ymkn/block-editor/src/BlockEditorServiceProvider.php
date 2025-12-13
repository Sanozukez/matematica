<?php

namespace Ymkn\BlockEditor;

use Illuminate\Support\ServiceProvider;

/**
 * Lafily Block Editor Service Provider
 * 
 * Registers the block editor package with Laravel/Filament
 */
class BlockEditorServiceProvider extends ServiceProvider
{
    /**
     * Register services
     */
    public function register(): void
    {
        // Merge package config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/block-editor.php',
            'block-editor'
        );
    }

    /**
     * Bootstrap services
     */
    public function boot(): void
    {
        $viewPath = __DIR__ . '/../resources/views';
        
        // Load views with namespace
        $this->loadViewsFrom($viewPath, 'block-editor-ymkn');

        // Publish config
        $this->publishes([
            __DIR__ . '/../config/block-editor.php' => config_path('block-editor.php'),
        ], 'block-editor-config');

        // Publish views
        $this->publishes([
            $viewPath => resource_path('views/vendor/block-editor'),
        ], 'block-editor-views');

        // Publish assets (CSS/JS)
        $this->publishes([
            __DIR__ . '/../resources/css' => public_path('vendor/block-editor/css'),
            __DIR__ . '/../resources/js' => public_path('vendor/block-editor/js'),
        ], ['block-editor-assets', 'laravel-assets']);
    }
}

