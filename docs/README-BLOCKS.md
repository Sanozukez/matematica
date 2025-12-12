# 📚 Documentação do Editor de Lições - v2.0

## 🎯 Início Rápido

Este diretório contém toda a documentação do **Editor de Lições Refatorado**.

### 📖 Documentos Disponíveis

1. **[REFACTORING-SUMMARY.md](./REFACTORING-SUMMARY.md)** 📝  
   **Comece aqui!** Resumo executivo da refatoração.
   
2. **[LESSON-EDITOR-ARCHITECTURE.md](./LESSON-EDITOR-ARCHITECTURE.md)** 🏗️  
   Arquitetura completa do sistema de blocos.
   
3. **[MIGRATION-GUIDE.md](./MIGRATION-GUIDE.md)** 🔄  
   Guia passo-a-passo para migração.
   
4. **[BLOCK-EXAMPLES.md](./BLOCK-EXAMPLES.md)** 💡  
   Exemplos práticos de uso e criação de blocos.

---

## 🚀 Guia Rápido

### **O que mudou?**

Antes você tinha blocos definidos inline no `LessonResource.php` (691 linhas bagunçadas).

Agora você tem blocos modulares:

```php
// Cada bloco em sua própria classe
app/Domain/Lesson/Blocks/ParagraphBlock.php
app/Domain/Lesson/Blocks/HeadingBlock.php
// ... 11 blocos no total
```

### **Como usar?**

```php
use App\Domain\Lesson\Services\BlockRegistry;
use App\Domain\Lesson\Services\LessonEditorService;

$blockRegistry = new BlockRegistry();
$editorService = new LessonEditorService($blockRegistry);

// Todos os blocos
$builder = $editorService->createBuilder();

// Apenas alguns blocos
$builder = $editorService->createBuilderWithBlocks([
    'paragraph',
    'heading',
    'image',
]);
```

### **Como adicionar novo bloco?**

1. Criar classe estendendo `AbstractBlock`
2. Implementar método `getSchema()`
3. Registrar em `BlockRegistry::registerDefaultBlocks()`

Veja exemplos em [BLOCK-EXAMPLES.md](./BLOCK-EXAMPLES.md).

---

## 📂 Estrutura da Documentação

```
docs/
├── README-BLOCKS.md                    # 👈 Você está aqui
├── REFACTORING-SUMMARY.md             # Resumo executivo
├── LESSON-EDITOR-ARCHITECTURE.md      # Arquitetura completa
├── MIGRATION-GUIDE.md                 # Guia de migração
├── BLOCK-EXAMPLES.md                  # Exemplos práticos
├── tiptap-editor-guide.md            # Guia do TipTap (antigo)
└── editor-implementation.md          # Implementação antiga (referência)
```

---

## ✅ Checklist Pós-Refatoração

- [ ] Executar `composer update` para remover Laraberg
- [ ] Executar `composer dump-autoload`
- [ ] Limpar cache Laravel
- [ ] Testar criação de lição
- [ ] Testar cada tipo de bloco
- [ ] Verificar se não há erros nos logs

---

## 🆘 Suporte

- **Arquitetura:** Veja [LESSON-EDITOR-ARCHITECTURE.md](./LESSON-EDITOR-ARCHITECTURE.md)
- **Migração:** Veja [MIGRATION-GUIDE.md](./MIGRATION-GUIDE.md)
- **Exemplos:** Veja [BLOCK-EXAMPLES.md](./BLOCK-EXAMPLES.md)
- **Código:** `app/Domain/Lesson/Blocks/`

---

**Versão:** 2.0.0  
**Data:** 11 de Dezembro de 2025  
**Status:** ✅ Refatoração Completa
