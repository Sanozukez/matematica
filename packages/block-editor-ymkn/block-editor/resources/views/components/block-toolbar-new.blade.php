{{-- Block Toolbar Universal - Refatorado e Modular --}}
{{-- WordPress-style floating toolbar --}}

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div 
    x-show="focusedBlockId === block.id"
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="block-toolbar-universal"
    x-data="{ 
        showColors: false,
        showAlign: false,
        showHeadingLevel: false,
        showMore: false,
        showLinkInput: false,
        linkUrl: ''
    }"
>
    {{-- Universal Controls (All Blocks) --}}
    @include('block-editor-ymkn::components.toolbar.block-controls')
    
    <div class="block-toolbar-divider"></div>
    
    {{-- PARAGRAPH: Alignment + Formatting + Colors --}}
    <template x-if="block.type === 'paragraph'">
        <div class="block-toolbar-section">
            @include('block-editor-ymkn::components.toolbar.alignment-dropdown')
            <div class="block-toolbar-divider"></div>
            @include('block-editor-ymkn::components.toolbar.text-formatting')
        </div>
    </template>
    
    {{-- HEADING: Level + Alignment + Formatting --}}
    <template x-if="block.type === 'heading'">
        <div class="block-toolbar-section">
            @include('block-editor-ymkn::components.toolbar.heading-level')
            <div class="block-toolbar-divider"></div>
            @include('block-editor-ymkn::components.toolbar.alignment-dropdown')
            <div class="block-toolbar-divider"></div>
            @include('block-editor-ymkn::components.toolbar.text-formatting')
        </div>
    </template>
    
    {{-- Color Picker (Paragraph & Heading) --}}
    <template x-if="block.type === 'paragraph' || block.type === 'heading'">
        @include('block-editor-ymkn::components.toolbar.color-picker-full')
    </template>
    
    <div class="block-toolbar-divider"></div>
    
    {{-- More Options (All Blocks) --}}
    <div class="block-toolbar-section">
        @include('block-editor-ymkn::components.toolbar.more-options')
    </div>
</div>
