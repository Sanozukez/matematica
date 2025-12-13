<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Block Editor Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the Lafily Block Editor behavior and appearance
    |
    */

    /**
     * Available block types
     * Set to null to enable all blocks, or specify an array of block names
     */
    'enabled_blocks' => null, // null = all blocks, or ['paragraph', 'heading', ...]

    /**
     * Default blocks to show in new content
     */
    'default_blocks' => [],

    /**
     * Editor layout configuration
     */
    'layout' => [
        'sidebar_left_width' => '280px',
        'sidebar_right_width' => '320px',
        'topbar_height' => '64px',
    ],

    /**
     * UI Customization
     */
    'ui' => [
        'brand_name' => 'Lafily Editor',
        'brand_icon' => '🧱',
        'show_block_count' => true,
        'collapsible_left_sidebar' => true,
    ],

    /**
     * Performance
     */
    'performance' => [
        'debounce_save' => 1000, // milliseconds
        'autosave' => true,
    ],
];
