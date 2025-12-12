# 🔄 Guia de Migração - Laraberg para Sistema Modular

## ✅ Checklist de Migração

### 1. Remover Laraberg ✅
- [x] Removido do `composer.json`
- [ ] Executar `composer update` para remover do vendor
- [ ] Verificar se não há imports de `VanOns\Laraberg` no código

### 2. Arquivos Criados ✅

```
✅ app/Domain/Lesson/Blocks/BlockContract.php
✅ app/Domain/Lesson/Blocks/AbstractBlock.php
✅ app/Domain/Lesson/Blocks/ParagraphBlock.php
✅ app/Domain/Lesson/Blocks/HeadingBlock.php
✅ app/Domain/Lesson/Blocks/ImageBlock.php
✅ app/Domain/Lesson/Blocks/VideoBlock.php
✅ app/Domain/Lesson/Blocks/CodeBlock.php
✅ app/Domain/Lesson/Blocks/QuoteBlock.php
✅ app/Domain/Lesson/Blocks/AlertBlock.php
✅ app/Domain/Lesson/Blocks/ListBlock.php
✅ app/Domain/Lesson/Blocks/LatexBlock.php
✅ app/Domain/Lesson/Blocks/DividerBlock.php
✅ app/Domain/Lesson/Blocks/TableBlock.php
✅ app/Domain/Lesson/Services/BlockRegistry.php
✅ app/Domain/Lesson/Services/LessonEditorService.php
```

### 3. Arquivos Refatorados ✅

```
✅ app/Filament/Resources/LessonResource.php (limpo, sem blocos inline)
✅ app/Filament/Resources/LessonResource/Pages/EditLessonFullscreen.php (usa BlockRegistry)
```

### 4. Documentação Criada ✅

```
✅ docs/LESSON-EDITOR-ARCHITECTURE.md
✅ docs/MIGRATION-GUIDE.md (este arquivo)
```

## 🚀 Passos Pós-Refatoração

### 1. Atualizar Dependências

```bash
cd plataforma
composer update
```

Isso removerá o Laraberg do `vendor/`.

### 2. Limpar Cache do Laravel

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 3. Verificar Assets do Filament

```bash
php artisan filament:optimize
```

### 4. Testar o Editor

1. Acesse **Admin → Lições**
2. Clique em **Criar Lição**
3. Preencha os campos básicos e salve
4. Clique em **Editor** para abrir o editor fullscreen
5. Teste adicionar cada tipo de bloco:
   - [ ] Parágrafo (TipTap)
   - [ ] Título (H2, H3, H4)
   - [ ] Imagem
   - [ ] Vídeo
   - [ ] Código
   - [ ] Citação
   - [ ] Alerta
   - [ ] Lista
   - [ ] LaTeX
   - [ ] Divisor
   - [ ] Tabela

### 5. Verificar Lições Existentes

```bash
# No tinker
php artisan tinker

# Verificar uma lição
$lesson = App\Domain\Lesson\Models\Lesson::first();
dd($lesson->content); // Deve ser array

# Validar conteúdo
$service = new App\Domain\Lesson\Services\LessonEditorService(
    new App\Domain\Lesson\Services\BlockRegistry()
);
$service->validateContent($lesson->content); // Deve retornar true
```

## 🐛 Problemas Comuns

### Erro: "Class BlockContract not found"

**Causa:** Autoload não está atualizado.

**Solução:**
```bash
composer dump-autoload
```

### Erro: "Call to undefined method make()"

**Causa:** Bloco não está estendendo `AbstractBlock` corretamente.

**Solução:** Verifique se a classe do bloco:
1. Estende `AbstractBlock`
2. Implementa método `getSchema()`
3. Chama `parent::createBlock()` em `make()`

### Blocos Não Aparecem no Editor

**Causa:** Bloco não foi registrado no `BlockRegistry`.

**Solução:** Adicione em `BlockRegistry::registerDefaultBlocks()`:
```php
$this->register(new SeuNovoBlock());
```

### Erro ao Salvar Conteúdo

**Causa:** Estrutura de dados incompatível.

**Solução:** Verifique se o model `Lesson` tem:
```php
protected $casts = [
    'content' => 'array',
];
```

## 📊 Comparação: Antes vs Depois

### Código do LessonResource

**Antes:**
```php
// 691 linhas
// 300+ linhas só de definição de blocos (inline)
// Código duplicado em múltiplos lugares
```

**Depois:**
```php
// 370 linhas
// Blocos gerenciados externamente
// Código limpo e organizado
```

**Redução:** ~46% menos código no Resource!

### Manutenibilidade

**Antes:**
- Adicionar campo em um bloco = alterar em 2+ lugares
- Difícil encontrar onde está a definição do bloco
- Testes impossíveis

**Depois:**
- Adicionar campo = alterar em 1 arquivo
- Cada bloco tem seu arquivo dedicado
- Testes unitários possíveis

## ✅ Validação Final

Execute este checklist para confirmar que tudo está funcionando:

### Funcional
- [ ] Editor fullscreen abre sem erros
- [ ] Todos os tipos de blocos aparecem na lista
- [ ] Possível adicionar cada tipo de bloco
- [ ] Blocos podem ser reordenados (drag & drop)
- [ ] Blocos podem ser clonados
- [ ] Blocos podem ser deletados
- [ ] Salvar funciona corretamente
- [ ] Conteúdo salvo carrega corretamente ao reabrir

### Técnico
- [ ] Nenhum erro no console do navegador
- [ ] Nenhum erro em `storage/logs/laravel.log`
- [ ] Composer não tem dependências quebradas
- [ ] Autoload funcionando (composer dump-autoload)
- [ ] Cache limpo

### Limpeza
- [ ] Código legado comentado removido
- [ ] Arquivos `.bak` podem ser deletados (após confirmar que tudo funciona)
- [ ] Imports não utilizados removidos

## 🔄 Rollback (Se Necessário)

Se algo der errado, você pode restaurar os arquivos originais:

```bash
# Restaurar LessonResource
cp app/Filament/Resources/LessonResource.php.bak app/Filament/Resources/LessonResource.php

# Restaurar EditLessonFullscreen
cp app/Filament/Resources/LessonResource/Pages/EditLessonFullscreen.php.bak app/Filament/Resources/LessonResource/Pages/EditLessonFullscreen.php

# Reinstalar Laraberg (não recomendado)
composer require van-ons/laraberg:^2.0
```

## 📚 Próximos Passos Recomendados

1. **Testar Extensivamente**
   - Criar diversas lições de teste
   - Testar cada tipo de bloco
   - Verificar renderização no frontend

2. **Adicionar Testes Automatizados**
   ```bash
   php artisan make:test Lesson/Blocks/ParagraphBlockTest --unit
   ```

3. **Documentar Blocos Customizados**
   - Se você criar blocos novos, documente-os
   - Adicione exemplos de uso

4. **Otimizar Frontend**
   - Criar componentes Blade para renderizar cada tipo de bloco
   - Adicionar estilos específicos

5. **Implementar Features Avançadas**
   - Preview em tempo real
   - Histórico de versões
   - Templates de lições

## 🆘 Suporte

Se encontrar problemas:

1. Verifique `storage/logs/laravel.log`
2. Consulte `docs/LESSON-EDITOR-ARCHITECTURE.md`
3. Revise o código dos blocos em `app/Domain/Lesson/Blocks/`

---

**Data da Migração:** 11 de Dezembro de 2025  
**Versão:** 2.0
