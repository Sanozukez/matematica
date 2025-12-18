# 🏗️ Arquitetura do Block Editor - Diagrama Visual

## 📐 Visão Geral da Arquitetura Modular

```
┌─────────────────────────────────────────────────────────────────┐
│                    🎯 BlockEditorCore.js                         │
│                     (Orquestrador - 350 linhas)                  │
│                                                                  │
│  Estado:                          Métodos Públicos:              │
│  • blocks[]                       • init()                       │
│  • focusedBlockId                 • addBlock()                   │
│  • lessonId                       • removeBlock()                │
│  • isSaving                       • save()                       │
│  • showBlockInserter             • focusBlock()                 │
│                                   • handleEnter()                │
└───────────────────────┬──────────────────────────────────────────┘
                        │
        ┌───────────────┼───────────────────────────────┐
        │               │                               │
        ▼               ▼                               ▼
┌──────────────┐ ┌──────────────┐              ┌──────────────┐
│ 📦 BLOCK     │ │ ⌨️ EVENT     │              │ 💾 STATE     │
│  MANAGER     │ │  HANDLERS    │              │  MANAGER     │
│              │ │              │              │              │
│ • addBlock   │ │ • handleEnter│              │ • loadBlocks │
│ • removeBlock│ │ • handleBack │              │ • saveBlocks │
│ • updateBlock│ │ • focusBlock │              │ • debouncer  │
│ • moveBlock  │ │ • canvasClick│              │              │
│ • duplicate  │ │ • listEvents │              │              │
│ • serialize  │ │ • tableEvents│              │              │
└──────────────┘ └──────────────┘              └──────────────┘
        │               │                               │
        │               │                               │
        ▼               ▼                               ▼
┌──────────────┐ ┌──────────────┐              ┌──────────────┐
│ 🖱️ DRAG &   │ │ 🎨 FORMAT    │              │ 🖼️ BLOCK     │
│   DROP MGR   │ │  MANAGER     │              │  RENDERERS   │
│              │ │              │              │              │
│ • dragStart  │ │ • bold       │              │ • imageUpload│
│ • dragOver   │ │ • italic     │              │ • videoEmbed │
│ • dragLeave  │ │ • underline  │              │ • latexRender│
│ • drop       │ │ • textColor  │              │              │
│ • dragEnd    │ │ • alignment  │              │              │
│ • resetState │ │ • insertLink │              │              │
└──────────────┘ └──────────────┘              └──────────────┘
```

---

## 🔄 Fluxo de Dados

### 1. **Inicialização**
```
User carrega página
    ↓
Alpine.js chama init()
    ↓
BlockEditorCore.init()
    ↓
StateManager.loadBlocks() ──► API: GET /api/lessons/:id/blocks
    ↓
blocks[] populado
    ↓
Alpine renderiza templates
```

### 2. **Adicionar Bloco**
```
User clica botão "+" ou digita Enter
    ↓
BlockEditorCore.addBlock('paragraph')
    ↓
BlockManager.addBlock(blocks, 'paragraph')
    ↓
blocks.push({ id, type, content, attributes })
    ↓
Alpine reage (x-for)
    ↓
Template Blade renderiza
    ↓
EventHandlers.focusBlock(newBlockId)
```

### 3. **Salvar**
```
User digita conteúdo
    ↓
@input="debouncedSave()"
    ↓
StateManager.createDebouncer().debounce()
    ↓
Aguarda 3 minutos de inatividade
    ↓
BlockEditorCore.saveBlocks()
    ↓
BlockManager.serializeBlocks() ──► Captura innerHTML
    ↓
StateManager.saveBlocks() ──► API: POST /api/lessons/:id/blocks
    ↓
showToast('Salvo com sucesso!')
```

### 4. **Formatação**
```
User seleciona texto e clica "Bold"
    ↓
@click="applyFormatting(block.id, 'bold')"
    ↓
BlockEditorCore.applyFormatting(blockId, 'bold')
    ↓
FormatManager.applyFormatting(blockId, 'bold')
    ↓
document.execCommand('bold', false, null)
    ↓
Texto fica <strong>negrito</strong>
    ↓
debouncedSave()
```

### 5. **Drag & Drop**
```
User arrasta bloco
    ↓
@dragstart="handleDragStart($event, block.id)"
    ↓
DragDropManager.handleDragStart()
    ↓
dragState.draggingBlockId = blockId
    ↓
User solta em outro bloco
    ↓
@drop="handleDrop($event, targetBlockId)"
    ↓
DragDropManager.handleDrop()
    ↓
blocks.splice() ──► Reordena array
    ↓
Alpine reage e re-renderiza
    ↓
debouncedSave()
```

---

## 🧩 Dependências entre Módulos

```
┌───────────────────────────────────────────────┐
│         BlockEditorCore (Orquestrador)        │
└───────────────────┬───────────────────────────┘
                    │
        ┌───────────┼───────────┐
        │           │           │
        ▼           ▼           ▼
    ┌───────┐  ┌───────┐  ┌───────┐
    │ Block │  │ Event │  │ State │
    │Manager│  │Handler│  │Manager│
    └───────┘  └───────┘  └───────┘
        │           │           │
        │           ▼           │
        │      ┌───────┐        │
        │      │ Drag  │        │
        │      │ Drop  │        │
        │      └───────┘        │
        │                       │
        ▼                       ▼
    ┌───────┐            ┌───────┐
    │Format │            │ Block │
    │Manager│            │Render │
    └───────┘            └───────┘
```

**Nota:** Módulos NÃO se comunicam diretamente. Apenas o Core orquestra.

---

## 📊 Responsabilidades por Camada

### **Camada 1: Orquestração** (Core)
- ✅ Gerencia estado global (blocks[], lessonId, etc)
- ✅ Integra Alpine.js com módulos
- ✅ Coordena fluxo entre módulos
- ❌ NÃO contém lógica de negócio

### **Camada 2: Lógica de Negócio** (Módulos)
- ✅ BlockManager: Manipulação de blocos
- ✅ EventHandlers: Interação do usuário
- ✅ StateManager: Persistência de dados
- ✅ FormatManager: Estilização de texto
- ✅ DragDropManager: Reordenação
- ✅ BlockRenderers: Transformações complexas

### **Camada 3: Apresentação** (Blade/Alpine)
- ✅ Templates Blade: HTML estático
- ✅ Alpine.js: Reatividade
- ✅ Tailwind CSS: Estilos

### **Camada 4: Dados** (API)
- ✅ Laravel Controllers: Endpoints
- ✅ Eloquent Models: Persistência DB
- ✅ JSON API: Comunicação

---

## 🎭 Princípios Aplicados

### 1. **Single Responsibility (SRP)**
✅ Cada módulo tem UMA responsabilidade clara

### 2. **Dependency Inversion**
✅ Core depende de abstrações (window.BlockManager)
✅ Módulos não dependem do Core

### 3. **Open/Closed Principle**
✅ Adicionar novos blocos = criar template Blade
✅ Não precisa modificar Core

### 4. **Don't Repeat Yourself (DRY)**
✅ Lógica de serialização centralizada
✅ Debouncer reutilizável

### 5. **Separation of Concerns**
✅ UI (Alpine) ≠ Lógica (Módulos) ≠ Dados (API)

---

## 🚀 Pontos de Extensão

### Adicionar Novo Tipo de Bloco
```
1. block-types.js ────► { type: 'gallery', label: '...' }
2. blocks/gallery.blade.php ────► Template HTML
3. (Opcional) BlockRenderers.js ────► Lógica complexa
4. editor.blade.php ────► <template x-if="...">
✅ PRONTO!
```

### Adicionar Nova Formatação
```
1. FormatManager.js ────► applyHighlight(blockId, color)
2. Toolbar component ────► Botão "Highlight"
✅ PRONTO!
```

### Integrar Nova API
```
1. StateManager.js ────► exportToPDF(lessonId)
2. BlockEditorCore.js ────► exportPDF() { StateManager... }
3. editor.blade.php ────► Botão "Exportar PDF"
✅ PRONTO!
```

---

## 📝 Exemplo de Uso

### Criar Bloco Customizado

```javascript
// 1. Adicionar tipo
// block-types.js
{
    type: 'callout',
    label: 'Caixa de Destaque',
    icon: '...',
    description: 'Box colorido com ícone'
}

// 2. Template
// blocks/callout.blade.php
<div class="bg-blue-50 border-l-4 border-blue-500 p-4">
    <div class="flex items-start">
        <svg class="w-6 h-6 text-blue-500">...</svg>
        <div 
            contenteditable="true"
            @input="updateBlockContent(block.id, $event.target.innerHTML)"
            x-html="block.content"
        ></div>
    </div>
</div>

// 3. Registrar
// editor.blade.php
<template x-if="block.type === 'callout'">
    @include('block-editor-ymkn::blocks.callout')
</template>

// ✅ PRONTO! Bloco customizado funcionando
```

---

**Arquitetura:** Modular + SRP + DRY  
**Padrão:** MVC-like (Model=API, View=Blade, Controller=Modules)  
**Framework:** Alpine.js + Laravel + Tailwind  
**Inspiração:** WordPress Gutenberg
