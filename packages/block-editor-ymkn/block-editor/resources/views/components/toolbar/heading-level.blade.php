{{-- Toolbar Section: Heading Level Selector (H1-H6) --}}
<div class="block-toolbar-dropdown">
    <button class="block-toolbar-btn block-toolbar-heading-level" title="Alterar nível do título"
            @click="showHeadingLevel = !showHeadingLevel"
            @click.outside="showHeadingLevel = false">
        <span x-text="'H' + (block.attributes.level || 2)"></span>
        <span class="material-icons" style="font-size: 14px;">expand_more</span>
    </button>
    
    <div x-show="showHeadingLevel" class="block-toolbar-dropdown-menu">
        <template x-for="level in [1,2,3,4,5,6]" :key="level">
            <button class="block-toolbar-dropdown-item" 
                    @click="updateBlockAttributes(block.id, { level }); showHeadingLevel = false">
                <span x-text="'H' + level" style="font-weight: bold; font-size: 14px;"></span>
                <span x-text="'Título ' + level"></span>
            </button>
        </template>
    </div>
</div>
