# Fluxo de Criação de Conteúdo

## 📋 Hierarquia e Relacionamentos

```
Curso (Course)
  ├── Módulo 1 (Module)
  │   ├── Lição 1.1 (Lesson)
  │   ├── Lição 1.2 (Lesson)
  │   └── Badge (opcional)
  ├── Módulo 2 (Module)
  │   ├── Lição 2.1 (Lesson)
  │   └── ...
  └── ...
```

## 🔑 Constraints Únicos

### Curso
- **Slug**: Único globalmente
- **Validação**: `unique('courses', 'slug')`

### Módulo
- **Slug**: Único por curso
- **Constraint DB**: `unique(['course_id', 'slug'])`
- **Validação Filament**: `unique` com `where('course_id', ...)`

### Lição
- **Slug**: Único por módulo
- **Constraint DB**: `unique(['module_id', 'slug'])`
- **Validação Filament**: `unique` com `where('module_id', ...)`

## 📝 Fluxo de Criação

### 1. Criar Curso

**Painel**: `Conteúdo → Cursos → Criar Curso`

**Campos obrigatórios**:
- Título (gera slug automaticamente)
- Nível (básico, fundamental, médio, avançado)

**Campos opcionais**:
- Descrição
- Ícone (emoji)
- Cor do tema
- Ordem de exibição
- Status (ativo/inativo)
- Gamificado (sim/não)

**Exemplo**:
```
Título: Matemática Básica
Slug: matematica-basica (gerado automaticamente)
Nível: Básico
Ícone: 🧮
Ativo: Sim
```

### 2. Criar Módulo

**Painel**: `Conteúdo → Módulos → Criar Módulo`

**Campos obrigatórios**:
- Curso (selecione o curso criado)
- Título (gera slug automaticamente)

**Campos opcionais**:
- Descrição
- Ícone (emoji)
- Ordem dentro do curso
- Status (ativo/inativo)

**Exemplo**:
```
Curso: Matemática Básica
Título: Números Naturais
Slug: numeros-naturais (gerado automaticamente)
Descrição: Introdução aos números naturais
Ícone: 🔢
Ordem: 1
Ativo: Sim
```

**Importante**: O slug deve ser único dentro do curso. Você pode ter "Introdução" em vários cursos, mas não no mesmo curso.

### 3. Criar Lição

**Painel**: `Conteúdo → Lições → Criar Lição`

**Campos obrigatórios**:
- Módulo (selecione o módulo criado)
- Título (gera slug automaticamente)
- Tipo de conteúdo (Texto, Vídeo, Quiz, Mini Jogo)

**Campos opcionais**:
- Duração estimada (minutos)
- Ordem dentro do módulo
- Status (ativo/inativo)

**Exemplo**:
```
Módulo: Números Naturais
Título: O que são números?
Slug: o-que-sao-numeros (gerado automaticamente)
Tipo: Texto/Conteúdo
Duração: 10 minutos
Ordem: 1
Ativo: Sim
```

**Importante**: O slug deve ser único dentro do módulo. Você pode ter "Introdução" em vários módulos, mas não no mesmo módulo.

## ⚠️ Erros Comuns e Soluções

### Erro: "Duplicate entry for key 'unique'"

**Causa**: Tentativa de criar registro com slug duplicado no mesmo escopo.

**Soluções**:

1. **Slug duplicado no mesmo módulo/curso**:
   - Altere o título para gerar um slug diferente
   - Ou edite manualmente o slug

2. **Registro já existe (soft deleted)**:
   - Verifique se o registro foi excluído (soft delete)
   - Restaure o registro antigo ou use um slug diferente

### Erro: "Módulo não aparece no dropdown"

**Causas possíveis**:
1. Nenhum módulo criado
2. Módulo está inativo (`is_active = false`)
3. Módulo foi soft deleted

**Solução**:
```bash
# Verificar módulos existentes
docker exec plataforma-laravel.test-1 php artisan tinker
>>> use App\Domain\Module\Models\Module;
>>> Module::get(['id', 'title', 'is_active']);

# Ativar módulo
>>> $module = Module::find('uuid-do-modulo');
>>> $module->is_active = true;
>>> $module->save();
```

### Erro: "Editor.js não carrega"

**Causas possíveis**:
1. Scripts CDN não carregaram
2. Conflito de JavaScript
3. Problema de rede

**Solução**:
- Limpe o cache do navegador
- Verifique o console do navegador (F12)
- Aguarde os scripts carregarem (indicador de loading)

## 🔄 Ordem de Criação Recomendada

1. **Curso** → Crie o curso principal
2. **Módulos** → Crie todos os módulos do curso
3. **Lições** → Crie as lições de cada módulo
4. **Badges** (opcional) → Associe badges aos módulos

## 📊 Validações Implementadas

### Curso
- ✅ Slug único globalmente
- ✅ Título obrigatório
- ✅ Nível obrigatório

### Módulo
- ✅ Slug único por curso
- ✅ Título obrigatório
- ✅ Curso obrigatório
- ✅ Validação de relacionamento

### Lição
- ✅ Slug único por módulo
- ✅ Título obrigatório
- ✅ Módulo obrigatório
- ✅ Tipo obrigatório
- ✅ Validação de relacionamento
- ✅ Content JSON (Editor.js)

## 🧪 Testando o Fluxo

### Teste Completo

```bash
# 1. Criar curso via Tinker
docker exec plataforma-laravel.test-1 php artisan tinker
>>> use App\Domain\Course\Models\Course;
>>> $course = Course::create([
...     'title' => 'Matemática Teste',
...     'slug' => 'matematica-teste',
...     'level' => 'basic',
...     'is_active' => true
... ]);

# 2. Criar módulo
>>> use App\Domain\Module\Models\Module;
>>> $module = Module::create([
...     'course_id' => $course->id,
...     'title' => 'Módulo Teste',
...     'slug' => 'modulo-teste',
...     'is_active' => true
... ]);

# 3. Criar lição via painel
# Acesse: http://localhost:8005/admin/lessons/create
# Selecione o módulo criado
# Preencha os campos
# Salve
```

## 🔍 Verificando Dados

```bash
# Ver cursos
docker exec plataforma-laravel.test-1 php artisan tinker
>>> Course::count();
>>> Course::get(['id', 'title']);

# Ver módulos
>>> Module::count();
>>> Module::with('course')->get(['id', 'title', 'course_id']);

# Ver lições
>>> Lesson::count();
>>> Lesson::with('module')->get(['id', 'title', 'module_id']);
```

## 📝 Notas Importantes

1. **Soft Deletes**: Todos os modelos usam soft deletes. Registros "excluídos" ainda existem no banco.

2. **Cascade Delete**: 
   - Excluir curso → exclui módulos → exclui lições
   - Cuidado ao excluir!

3. **Slugs Automáticos**: Gerados automaticamente do título, mas podem ser editados manualmente.

4. **Relacionamentos**: Sempre verifique se o registro pai existe e está ativo antes de criar filhos.

5. **Editor.js**: O conteúdo é salvo como JSON puro, nunca como HTML.

