<?php

namespace Lafily\BlockEditor;

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
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/block-editor.php' => config_path('block-editor.php'),
        ], 'block-editor-config');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'block-editor');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/block-editor'),
        ], 'block-editor-views');

        // Publish assets
        $this->publishes([
            __DIR__ . '/../resources/css' => public_path('vendor/block-editor/css'),
            __DIR__ . '/../resources/js' => public_path('vendor/block-editor/js'),
        ], 'block-editor-assets');
    }
}
