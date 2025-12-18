# Block Editor - Arquitetura Modular

## 📁 Estrutura do Projeto (REFATORADA)

```
resources/js/
├── BlockEditorCore.js           # 🎯 Core (350 linhas) - Orquestrador principal
├── block-types.js               # 📦 Definição dos tipos de blocos disponíveis
├── BlockEditorCore-old.js       # 🗄️ Backup (779 linhas) - versão monolítica anterior
└── modules/                     # 🧩 Módulos especializados
    ├── BlockManager.js          # CRUD de blocos (add, remove, update, move, duplicate)
    ├── EventHandlers.js         # Eventos de teclado, mouse, navegação
    ├── DragDropManager.js       # Arrastar e soltar blocos
    ├── FormatManager.js         # Formatação de texto (bold, colors, alignment, links)
    ├── BlockRenderers.js        # Renderizadores específicos (image, video, latex)
    └── StateManager.js          # Persistência (save/load, debounce, API calls)
```

## ✨ Melhorias Implementadas

### 1. **Modularização (55% redução de linhas)**
   - **Antes:** 1 arquivo com 779 linhas
   - **Depois:** 1 core + 6 módulos especializados
   - **Core:** 350 linhas (apenas orquestração)
   - **Cada módulo:** 50-150 linhas (responsabilidade única)

### 2. **Blocos Faltantes Adicionados**
   ✅ Todos os 11 tipos de blocos agora aparecem no editor:
   - `paragraph` ✓
   - `heading` ✓
   - `quote` ✓
   - `code` ✓
   - `divider` ✓
   - `image` ✓ (NOVO)
   - `video` ✓ (NOVO)
   - `list` ✓ (NOVO)
   - `alert` ✓ (NOVO)
   - `latex` ✓ (NOVO)
   - `table` ✓ (NOVO)

### 3. **Princípio SRP (Single Responsibility Principle)**
   Cada módulo tem UMA responsabilidade clara:
   
   | Módulo | Responsabilidade |
   |--------|------------------|
   | `BlockManager` | Gerenciar ciclo de vida dos blocos |
   | `EventHandlers` | Lidar com eventos de usuário |
   | `DragDropManager` | Controlar drag & drop |
   | `FormatManager` | Aplicar formatação de texto |
   | `BlockRenderers` | Renderizar blocos complexos |
   | `StateManager` | Persistir dados no servidor |

### 4. **Código Limpo**
   - ❌ Removido: `block-editor.js` (324 linhas duplicadas/não usadas)
   - 📦 Backup criado: `BlockEditorCore-old.js`
   - 🧹 Organização profissional com separação de concerns

## 🔧 Como Usar os Módulos

### Exemplo: Adicionar um novo bloco
```javascript
// No BlockEditorCore.js
addBlock(type = 'paragraph', afterIndex = null) {
    const newBlock = window.BlockManager.addBlock(this.blocks, type, afterIndex);
    this.$nextTick(() => {
        this.focusBlock(newBlock.id);
    });
    return newBlock;
}
```

### Exemplo: Salvar no servidor
```javascript
// StateManager lida com toda a lógica de persistência
async saveBlocks() {
    const result = await window.StateManager.saveBlocks(
        this.lessonId,
        this.blocks,
        this.lessonTitle
    );
    
    if (result.success) {
        this.showToast('Salvo com sucesso!');
    }
}
```

## 📝 Ordem de Carregamento (editor.blade.php)

```html
<!-- 1. Definições -->
<script src="js/block-types.js"></script>

<!-- 2. Módulos (ANTES do Core) -->
<script src="js/modules/BlockManager.js"></script>
<script src="js/modules/EventHandlers.js"></script>
<script src="js/modules/DragDropManager.js"></script>
<script src="js/modules/FormatManager.js"></script>
<script src="js/modules/BlockRenderers.js"></script>
<script src="js/modules/StateManager.js"></script>

<!-- 3. Core (usa os módulos) -->
<script src="js/BlockEditorCore.js"></script>
```

## 🚀 Próximos Passos Sugeridos

1. **Implementar blocos avançados**
   - [ ] Galeria de imagens
   - [ ] Accordion/Collapse
   - [ ] Tabs
   - [ ] Embed genérico (Twitter, Instagram, etc)

2. **Melhorar UX**
   - [ ] Undo/Redo
   - [ ] Copiar/Colar blocos
   - [ ] Atalhos de teclado
   - [ ] Auto-save visual feedback

3. **Performance**
   - [ ] Lazy loading de blocos
   - [ ] Virtual scrolling para muitos blocos
   - [ ] Debounce inteligente por tipo de bloco

4. **Acessibilidade**
   - [ ] ARIA labels
   - [ ] Navegação por teclado completa
   - [ ] Screen reader support

## 🐛 Debugging

Se os blocos não aparecerem:
1. Verifique o console: `✅ Block Editor iniciado (versão modular)`
2. Confirme que todos os módulos carregaram: `window.BlockManager`, `window.StateManager`, etc
3. Verifique se os templates Blade existem em `resources/views/blocks/`

## 📚 Documentação Adicional

- **Block Types:** `block-types.js` - Define todos os blocos disponíveis
- **Blade Templates:** `resources/views/blocks/*.blade.php` - HTML de cada bloco
- **CSS:** `resources/css/block-editor.css` - Estilos do editor

---

**Versão:** 2.0 (Modular)  
**Data:** Dezembro 2025  
**Autor:** Refatoração arquitetural para manutenibilidade e escalabilidade
