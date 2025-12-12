# Implementação do Editor de Aulas

## 📋 Resumo da Implementação

A criação de aulas usa **Editor.js** (block-style editor) integrado com **FilamentPHP** e **Laravel 11**.

### Stack Tecnológica

- **Frontend:** Editor.js (Block-style editor)
- **Backend:** Laravel 11
- **Banco de Dados:** MySQL (Coluna `content` do tipo `JSON`)
- **Renderização:** Conteúdo salvo como estrutura JSON limpa (não HTML)
- **Fórmulas:** LaTeX via KaTeX (plugin customizado)

## 🔑 Regra de Ouro: Gerenciamento de Imagens

**NUNCA salvar hotlinks externos no banco de dados.**

### Fluxo de Imagens

1. **Upload Direto:**
   - Usuário arrasta imagem → Backend salva no Storage → Retorna URL definitiva

2. **Paste (Ctrl+V) ou Importação:**
   - Backend intercepta URL original
   - Backend faz download da imagem (via HTTP Client)
   - Backend faz upload para nosso Storage
   - JSON final contém apenas URL do nosso domínio/CDN

## 📁 Estrutura de Arquivos

```
plataforma/
├── app/
│   ├── Services/
│   │   └── StorageService.php          # Serviço de storage (local/Bunny.net)
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   │           └── UploadController.php  # Controller de upload
│   └── Forms/
│       └── Components/
│           └── EditorJsField.php        # Campo customizado Filament
├── resources/
│   └── views/
│       └── components/
│           └── editor-js.blade.php     # View do Editor.js
└── database/
    └── migrations/
        └── 2025_12_10_191914_create_lessons_table.php
```

## 🔧 Componentes Principais

### 1. StorageService

**Localização:** `app/Services/StorageService.php`

Gerencia uploads de imagens com suporte para:
- Storage local (temporário)
- Bunny.net (preparado para ativação futura)

**Métodos principais:**
- `uploadFile($file, $subfolder)` - Upload de arquivo local
- `fetchAndStoreImage($url, $subfolder)` - Download e armazenamento de URL externa

### 2. UploadController

**Localização:** `app/Http/Controllers/Api/UploadController.php`

**Endpoints:**
- `POST /api/admin/upload/image` - Upload direto (byFile)
- `POST /api/admin/upload/image?byUrl=1` - Download de URL (byUrl)

**Resposta (formato Editor.js):**
```json
{
  "success": 1,
  "file": {
    "url": "https://exemplo.com/storage/lessons/images/2024/12/uuid.jpg"
  }
}
```

### 3. EditorJsField

**Localização:** `app/Forms/Components/EditorJsField.php`

Campo customizado do Filament que integra Editor.js com formulários.

### 4. editor-js.blade.php

**Localização:** `resources/views/components/editor-js.blade.php`

View Blade que inicializa o Editor.js com:
- Blocos: Texto, Título, Lista, Imagem, Código, Fórmula (LaTeX), Citação, Aviso, Tabela
- Plugin KaTeX para fórmulas matemáticas
- Configuração do ImageTool com endpoints de upload

## 📊 Estrutura do JSON (Editor.js)

O conteúdo é salvo como JSON puro no banco:

```json
{
  "time": 1234567890,
  "blocks": [
    {
      "type": "paragraph",
      "data": {
        "text": "Texto da lição..."
      }
    },
    {
      "type": "image",
      "data": {
        "file": {
          "url": "https://exemplo.com/storage/lessons/images/2024/12/uuid.jpg"
        },
        "caption": "Legenda da imagem",
        "withBorder": false,
        "stretched": false,
        "withBackground": false
      }
    },
    {
      "type": "math",
      "data": {
        "latex": "\\frac{a}{b}",
        "displayMode": true
      }
    }
  ],
  "version": "2.28.0"
}
```

## 🚀 Como Usar

### No Filament (Painel Admin)

1. Acesse **Conteúdo → Lições**
2. Clique em **Criar Lição**
3. Selecione tipo **Texto**
4. Use o editor para adicionar blocos:
   - Clique no **+** para adicionar blocos
   - Arraste imagens ou cole URLs (download automático)
   - Use o bloco **Fórmula** para LaTeX

### Upload de Imagens

**Upload Direto:**
- Arraste imagem para o editor
- Ou clique em "Selecionar imagem"

**Paste de URL:**
- Cole URL de imagem (Ctrl+V)
- Sistema faz download automático
- Salva no nosso storage
- URL externa nunca é salva

## 🔄 Migração para Bunny.net

Quando ativar o Bunny.net:

1. Adicione no `.env`:
```env
BUNNY_STORAGE_ZONE=seu-storage-zone
BUNNY_STORAGE_API_KEY=sua-api-key
BUNNY_CDN_URL=https://seu-cdn.b-cdn.net
```

2. Descomente a configuração em `config/filesystems.php`:
```php
'bunny' => [
    // ... configuração
]
```

3. Pronto! O `StorageService` detecta automaticamente e migra.

**Não é necessário alterar código** - apenas configuração.

## ✅ Checklist de Implementação

- [x] Migration com coluna `content` JSON
- [x] StorageService preparado para Bunny.net
- [x] UploadController com download de URLs externas
- [x] Editor.js configurado com ImageTool
- [x] Plugin LaTeX (KaTeX) integrado
- [x] Regra de Ouro implementada (nunca hotlinks)
- [x] Documentação completa

## 📝 Próximos Passos (Futuro)

- [ ] Importação de Google Docs (converter para blocos Editor.js)
- [ ] Importação de Word (.docx)
- [ ] Processamento de imagens (redimensionamento, otimização)
- [ ] Cache de renderização do JSON para HTML

