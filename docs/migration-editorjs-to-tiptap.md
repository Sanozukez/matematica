# 🔄 Migração: Editor.js → TipTap

## 📋 Resumo da Migração

### O Que Mudou

| Aspecto | Editor.js | TipTap |
|---------|-----------|--------|
| **Pacote** | Custom (CDN) | `awcodes/filament-tiptap-editor` |
| **Interface** | Minimalista | Barra de ferramentas completa |
| **Formatação** | Limitada | Rica (cores, alinhamento, etc) |
| **Dados** | JSON (blocos) | JSON (ProseMirror) |
| **LaTeX** | Plugin custom | Extensão (em dev) |
| **Upload** | Controller custom | Integrado Filament |
| **UX** | Básica | Profissional (WordPress-like) |

## 🗑️ Arquivos Removidos

```
✗ app/Forms/Components/EditorJsField.php
✗ resources/views/components/editor-js.blade.php
✗ public/js/editor-loader.js
```

## ✅ Arquivos Adicionados

```
✓ config/filament-tiptap-editor.php
✓ app/Filament/Resources/LessonResource/Pages/EditLessonFullscreen.php
✓ resources/views/filament/resources/lesson-resource/pages/edit-lesson-fullscreen.blade.php
✓ resources/js/tiptap-math-extension.js (LaTeX)
✓ resources/views/vendor/filament-tiptap-editor/tiptap-math.blade.php
✓ docs/tiptap-editor-guide.md
```

## 🔄 Migração de Dados

### Estrutura Editor.js

```json
{
  "time": 1702377600000,
  "blocks": [
    {
      "id": "abc123",
      "type": "paragraph",
      "data": {
        "text": "Texto do parágrafo"
      }
    },
    {
      "id": "def456",
      "type": "header",
      "data": {
        "text": "Título",
        "level": 2
      }
    }
  ],
  "version": "2.28.2"
}
```

### Estrutura TipTap

```json
{
  "type": "doc",
  "content": [
    {
      "type": "paragraph",
      "content": [
        {
          "type": "text",
          "text": "Texto do parágrafo"
        }
      ]
    },
    {
      "type": "heading",
      "attrs": {
        "level": 2
      },
      "content": [
        {
          "type": "text",
          "text": "Título"
        }
      ]
    }
  ]
}
```

## 🔧 Script de Migração

Se você tem lições criadas com Editor.js, use este script:

```php
<?php

// plataforma/app/Console/Commands/MigrateEditorJsToTiptap.php

namespace App\Console\Commands;

use App\Domain\Lesson\Models\Lesson;
use Illuminate\Console\Command;

class MigrateEditorJsToTiptap extends Command
{
    protected $signature = 'lessons:migrate-editor';
    protected $description = 'Migra conteúdo de Editor.js para TipTap';

    public function handle()
    {
        $lessons = Lesson::where('type', 'text')->get();
        
        $this->info("Encontradas {$lessons->count()} lições para migrar");
        
        $bar = $this->output->createProgressBar($lessons->count());
        
        foreach ($lessons as $lesson) {
            if (empty($lesson->content)) {
                $bar->advance();
                continue;
            }
            
            $editorJsContent = $lesson->content;
            $tiptapContent = $this->convertToTiptap($editorJsContent);
            
            $lesson->update(['content' => $tiptapContent]);
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('✅ Migração concluída!');
    }
    
    private function convertToTiptap(array $editorJsContent): array
    {
        $tiptapDoc = [
            'type' => 'doc',
            'content' => [],
        ];
        
        if (!isset($editorJsContent['blocks'])) {
            return $tiptapDoc;
        }
        
        foreach ($editorJsContent['blocks'] as $block) {
            $tiptapBlock = $this->convertBlock($block);
            if ($tiptapBlock) {
                $tiptapDoc['content'][] = $tiptapBlock;
            }
        }
        
        return $tiptapDoc;
    }
    
    private function convertBlock(array $block): ?array
    {
        $type = $block['type'] ?? 'paragraph';
        $data = $block['data'] ?? [];
        
        switch ($type) {
            case 'paragraph':
                return [
                    'type' => 'paragraph',
                    'content' => [
                        ['type' => 'text', 'text' => $data['text'] ?? '']
                    ],
                ];
                
            case 'header':
                return [
                    'type' => 'heading',
                    'attrs' => ['level' => $data['level'] ?? 2],
                    'content' => [
                        ['type' => 'text', 'text' => $data['text'] ?? '']
                    ],
                ];
                
            case 'list':
                return [
                    'type' => $data['style'] === 'ordered' ? 'orderedList' : 'bulletList',
                    'content' => array_map(function($item) {
                        return [
                            'type' => 'listItem',
                            'content' => [
                                [
                                    'type' => 'paragraph',
                                    'content' => [
                                        ['type' => 'text', 'text' => $item]
                                    ]
                                ]
                            ]
                        ];
                    }, $data['items'] ?? []),
                ];
                
            case 'image':
                return [
                    'type' => 'image',
                    'attrs' => [
                        'src' => $data['file']['url'] ?? '',
                        'alt' => $data['caption'] ?? '',
                        'title' => $data['caption'] ?? '',
                    ],
                ];
                
            case 'code':
                return [
                    'type' => 'codeBlock',
                    'content' => [
                        ['type' => 'text', 'text' => $data['code'] ?? '']
                    ],
                ];
                
            case 'quote':
                return [
                    'type' => 'blockquote',
                    'content' => [
                        [
                            'type' => 'paragraph',
                            'content' => [
                                ['type' => 'text', 'text' => $data['text'] ?? '']
                            ]
                        ]
                    ],
                ];
                
            case 'delimiter':
                return [
                    'type' => 'horizontalRule',
                ];
                
            case 'table':
                // Tabelas são mais complexas, implementar se necessário
                return null;
                
            default:
                $this->warn("Tipo de bloco não suportado: {$type}");
                return null;
        }
    }
}
```

### Executar Migração

```bash
docker exec plataforma-laravel.test-1 php artisan lessons:migrate-editor
```

## ⚠️ Notas Importantes

### 1. Backup Antes de Migrar

```bash
docker exec plataforma-mysql-1 mysqldump -u root -p matematica > backup_pre_migration.sql
```

### 2. Lições Novas

Lições criadas após a migração já usam TipTap automaticamente.

### 3. Compatibilidade

- ✅ Texto simples: 100% compatível
- ✅ Títulos: 100% compatível
- ✅ Listas: 100% compatível
- ✅ Imagens: 100% compatível
- ✅ Código: 100% compatível
- ✅ Citações: 100% compatível
- ⚠️ LaTeX: Requer re-edição (formato diferente)
- ⚠️ Tabelas: Pode precisar ajustes

## 🎯 Checklist Pós-Migração

- [ ] Executar script de migração
- [ ] Testar lições migradas no frontend
- [ ] Verificar imagens (URLs corretas)
- [ ] Re-adicionar fórmulas LaTeX (se houver)
- [ ] Limpar cache: `php artisan view:clear`
- [ ] Backup do banco pós-migração

## 🆘 Rollback (Se Necessário)

Se algo der errado:

1. **Restaurar backup**:
```bash
docker exec -i plataforma-mysql-1 mysql -u root -p matematica < backup_pre_migration.sql
```

2. **Reinstalar Editor.js**:
```bash
git checkout HEAD -- app/Forms/Components/EditorJsField.php
git checkout HEAD -- resources/views/components/editor-js.blade.php
```

3. **Remover TipTap**:
```bash
docker exec plataforma-laravel.test-1 composer remove awcodes/filament-tiptap-editor
```

## 📞 Suporte

Se encontrar problemas:

1. Verifique logs: `storage/logs/laravel.log`
2. Console do navegador (F12)
3. Teste com lição nova primeiro
4. Documente o erro e contexto

## 🎉 Benefícios Pós-Migração

- ✅ Interface mais profissional
- ✅ Mais opções de formatação
- ✅ Melhor UX para professores
- ✅ Modo fullscreen
- ✅ Atalhos de teclado
- ✅ Manutenção mais fácil
- ✅ Comunidade ativa (Filament)

