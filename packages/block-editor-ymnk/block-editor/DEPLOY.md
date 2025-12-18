# 🚀 Guia de Deploy - Block Editor Modular

## 📦 Republicar Assets após Refatoração

Após a modularização, é necessário republicar os assets JavaScript para o diretório `public/`:

### 1. Via Artisan (Recomendado)
```bash
# Republicar APENAS os assets JS/CSS
php artisan vendor:publish --tag=block-editor-assets --force

# OU publicar tudo (config + views + assets)
php artisan vendor:publish --provider="Ymkn\BlockEditor\BlockEditorServiceProvider" --force
```

### 2. Via Docker (se usando container)
```bash
# Se o Laravel está em container
docker compose -f "docker-compose.dev.yml" exec laravel.test php artisan vendor:publish --tag=block-editor-assets --force
```

### 3. Verificar Assets Publicados
```bash
# Windows PowerShell
Get-ChildItem -Path "public/vendor/block-editor/js" -Recurse

# Deve mostrar:
# - block-types.js
# - BlockEditorCore.js
# - BlockEditorCore-old.js (backup)
# - modules/
#   ├── BlockManager.js
#   ├── EventHandlers.js
#   ├── DragDropManager.js
#   ├── FormatManager.js
#   ├── BlockRenderers.js
#   └── StateManager.js
```

## 🔄 Workflow Completo

```bash
# 1. Republicar assets
php artisan vendor:publish --tag=block-editor-assets --force

# 2. Limpar caches
php artisan view:clear
php artisan config:clear
php artisan cache:clear

# 3. (Opcional) Rebuildar frontend se usar Vite/Mix
npm run build
# OU para dev
npm run dev
```

## ✅ Checklist de Verificação

Após republicar, verifique:

- [ ] Arquivo `public/vendor/block-editor/js/BlockEditorCore.js` existe (350 linhas)
- [ ] Pasta `public/vendor/block-editor/js/modules/` existe com 6 arquivos
- [ ] Backup `public/vendor/block-editor/js/BlockEditorCore-old.js` existe (779 linhas)
- [ ] Arquivo `public/vendor/block-editor/js/block-editor.js` NÃO existe (removido)
- [ ] Console do navegador mostra: `✅ Block Editor iniciado (versão modular)`
- [ ] Todos os 11 tipos de blocos aparecem no inserter

## 🐛 Troubleshooting

### Blocos não aparecem
```bash
# 1. Limpar view cache
php artisan view:clear

# 2. Republicar views
php artisan vendor:publish --tag=block-editor-views --force

# 3. Verificar namespace Blade
# Em editor.blade.php deve ser: @include('block-editor-ymkn::blocks.image')
```

### Erro 404 nos scripts
```bash
# 1. Verificar se assets foram publicados
ls public/vendor/block-editor/js/modules/

# 2. Se vazio, republicar
php artisan vendor:publish --tag=block-editor-assets --force

# 3. Verificar permissões (Linux/Mac)
chmod -R 755 public/vendor/block-editor/
```

### Console mostra "BlockManager is not defined"
**Causa:** Módulos não carregaram antes do Core

**Solução:** Verificar ordem de carregamento em `editor.blade.php`:
```html
<!-- Módulos ANTES do Core -->
<script src="{{ asset('vendor/block-editor/js/modules/BlockManager.js') }}"></script>
<!-- ... outros módulos ... -->
<script src="{{ asset('vendor/block-editor/js/BlockEditorCore.js') }}"></script>
```

## 🔐 Ambiente de Produção

```bash
# 1. Otimizar autoload
composer dump-autoload --optimize

# 2. Cache de config/routes/views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Build de assets minificados
npm run build
```

## 📊 Comparação Antes vs Depois

| Aspecto | Antes | Depois |
|---------|-------|--------|
| **Arquivos JS** | 2 arquivos | 8 arquivos (1 core + 6 módulos + 1 definições) |
| **Linhas de código** | 779 (monolítico) | 350 (core) + ~600 (módulos) |
| **Manutenibilidade** | ⚠️ Difícil | ✅ Fácil |
| **Blocos funcionais** | 5 de 11 (45%) | 11 de 11 (100%) |
| **Testabilidade** | ❌ Baixa | ✅ Alta (módulos isolados) |

---

**Última atualização:** Dezembro 2025  
**Versão do pacote:** 2.0 (Modular)
