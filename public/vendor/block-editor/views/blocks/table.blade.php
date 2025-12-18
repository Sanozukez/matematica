{{-- Table Block Component --}}
<div 
    class="block-wrapper block-table-wrapper" 
    :data-block-id="block.id"
    :class="{ 'block-focused': focusedBlockId === block.id }"
    @click="focusedBlockId = block.id"
    @dragover="handleDragOver($event, block.id)"
    @dragleave="handleDragLeave($event)"
    @drop="handleDrop($event, block.id)"
>
    {{-- Toolbar Universal --}}
    @include('block-editor-ymnk::components.block-toolbar')
    
    <div class="block-content">
        <div class="block-table-container">
            <table class="block-table">
                <thead x-show="block.attributes?.hasHeader !== false">
                    <tr>
                        <template x-for="(cell, colIndex) in (block.content?.[0] || ['', '', ''])" :key="'header-' + colIndex">
                            <th 
                                class="block-table-cell block-table-header"
                                contenteditable="true"
                                @input="updateTableCell(block.id, 0, colIndex, $event.target.textContent)"
                                @focus="focusedBlockId = block.id"
                                x-text="cell"
                            ></th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(row, rowIndex) in (block.content?.slice(1) || [['', '', '']])" :key="'row-' + rowIndex">
                        <tr>
                            <template x-for="(cell, colIndex) in row" :key="'cell-' + rowIndex + '-' + colIndex">
                                <td 
                                    class="block-table-cell"
                                    contenteditable="true"
                                    @input="updateTableCell(block.id, rowIndex + 1, colIndex, $event.target.textContent)"
                                    @focus="focusedBlockId = block.id"
                                    x-text="cell"
                                ></td>
                            </template>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
    
    <style>
        .block-table-wrapper {
            position: relative;
            margin: 1.5rem 0;
            transition: all 0.1s ease;
        }
        
        .block-table-wrapper:hover {
            outline: 1px solid #E0E0E0;
            outline-offset: -1px;
        }
        
        .block-table-wrapper.block-focused {
            outline: 2px solid #007CBA;
            outline-offset: -2px;
        }
        
        .block-table-container {
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
        }
        
        .block-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        
        .block-table-cell {
            padding: 0.75rem 1rem;
            border: 1px solid #E5E7EB;
            font-size: 0.9375rem;
            line-height: 1.6;
            color: #1E1E1E;
            outline: none;
            min-width: 100px;
        }
        
        .block-table-header {
            background: #F9FAFB;
            font-weight: 600;
            text-align: left;
        }
        
        .block-table-cell:empty:before {
            content: 'Digite...';
            color: #9CA3AF;
            pointer-events: none;
        }
        
        .block-table-cell:focus {
            background: #F0F9FF;
            border-color: #007CBA;
        }
    </style>
</div>
