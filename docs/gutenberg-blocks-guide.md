# 📦 Guia Completo: Sistema de Blocos Gutenberg

## 🎯 Visão Geral

O sistema de lições agora usa **Builder + RichEditor** nativos do Filament, criando uma experiência tipo **WordPress Gutenberg** com blocos arrastáveis e modulares.

### ✅ Vantagens

- **100% Nativo**: Sem dependências externas
- **Blocos Modulares**: 11 tipos de blocos pré-configurados
- **Arrastável**: Reordene blocos facilmente
- **Formatação Rica**: RichEditor com negrito, itálico, listas, links
- **Extensível**: Fácil adicionar novos blocos

## 📦 Blocos Disponíveis

### 1. 📝 Parágrafo
**Uso**: Texto corrido com formatação rica

**Campos**:
- `content` (RichEditor): Texto com negrito, itálico, listas, links

**Exemplo de dados**:
```json
{
  "type": "paragraph",
  "data": {
    "content": "<p>Este é um <strong>parágrafo</strong> com <em>formatação</em>.</p>"
  }
}
```

### 2. 📌 Título
**Uso**: Títulos e subtítulos (H2, H3, H4)

**Campos**:
- `level`: h2, h3, h4
- `content`: Texto do título
- `color`: Cor opcional (hex)

**Exemplo**:
```json
{
  "type": "heading",
  "data": {
    "level": "h2",
    "content": "Introdução à Geometria",
    "color": "#3b82f6"
  }
}
```

### 3. 🖼️ Imagem
**Uso**: Fotos, diagramas, gráficos

**Campos**:
- `file`: Upload de imagem (com editor)
- `alt`: Texto alternativo
- `caption`: Legenda
- `alignment`: left, center, right, wide

**Características**:
- ✅ Editor de imagem integrado
- ✅ Crop com proporções (16:9, 4:3, 1:1)
- ✅ Max 10MB
- ✅ Lazy loading
- ✅ Shadow e rounded

### 4. 📋 Lista
**Uso**: Listas de itens

**Campos**:
- `style`: bullet, numbered, checklist
- `items[]`: Array de itens com RichEditor

**Exemplo**:
```json
{
  "type": "list",
  "data": {
    "style": "bullet",
    "items": [
      {"content": "Primeiro item"},
      {"content": "Segundo item"}
    ]
  }
}
```

### 5. 💬 Citação
**Uso**: Citações de autores, livros, artigos

**Campos**:
- `content`: Texto da citação
- `author`: Nome do autor
- `source`: Livro/artigo

**Visual**: Borda azul à esquerda, fundo azul claro

### 6. 💻 Código
**Uso**: Exemplos de código com syntax highlighting

**Campos**:
- `language`: javascript, python, php, html, css, sql, bash, json
- `content`: Código
- `caption`: Título/legenda

**Características**:
- ✅ Syntax highlighting (Highlight.js)
- ✅ Tema Atom One Dark
- ✅ Scroll horizontal automático

### 7. ⚠️ Alerta
**Uso**: Avisos, notas importantes, dicas

**Campos**:
- `type`: info, success, warning, danger
- `title`: Título opcional
- `content`: Mensagem

**Cores**:
- Info: Azul (ℹ️)
- Success: Verde (✅)
- Warning: Amarelo (⚠️)
- Danger: Vermelho (⛔)

### 8. 🎥 Vídeo
**Uso**: YouTube, Vimeo, Bunny.net

**Campos**:
- `provider`: youtube, vimeo, bunny
- `url`: URL do vídeo
- `caption`: Descrição/notas

**Características**:
- ✅ Embed responsivo (16:9)
- ✅ Extração automática de ID
- ✅ Fallback para links inválidos

### 9. ∑ LaTeX (Fórmulas)
**Uso**: Fórmulas matemáticas

**Campos**:
- `content`: Código LaTeX
- `display_mode`: true (centralizado) ou false (inline)
- `caption`: Legenda

**Exemplos de LaTeX**:
```latex
\frac{a}{b}           → Fração
\sqrt{x}              → Raiz quadrada
x^{2}                 → Potência
\sum_{i=1}^{n} x_i    → Somatório
\int_{a}^{b} f(x) dx  → Integral
```

**Características**:
- ✅ Renderizado com KaTeX
- ✅ Fundo cinza claro
- ✅ Erro amigável se sintaxe incorreta

### 10. ━ Divisor
**Uso**: Separar seções

**Campos**:
- `style`: solid, dashed, dotted, thick, space

**Estilos**:
- Solid: Linha contínua
- Dashed: Linha tracejada
- Dotted: Linha pontilhada
- Thick: Linha grossa
- Space: Espaço em branco (sem linha)

### 11. 📊 Tabela
**Uso**: Dados tabulares, comparações

**Campos**:
- `caption`: Título da tabela
- `headers[]`: Cabeçalhos das colunas
- `rows[]`: Array de linhas
  - `cells[]`: Array de células

**Características**:
- ✅ Responsiva (scroll horizontal)
- ✅ Hover nas linhas
- ✅ Cabeçalho destacado

## 🎨 Como Usar no Admin

### Criando uma Lição

1. **Admin → Lições → Criar**
2. Preencha:
   - Módulo
   - Título
   - Slug (auto-gerado)
   - Tipo: **Texto/Conteúdo**
3. Clique em **"➕ Adicionar Bloco"**
4. Escolha o tipo de bloco
5. Preencha os campos
6. Arraste para reordenar
7. **Salvar**

### Dicas de UX

- **Reordenar**: Arraste o ícone ⋮⋮ ao lado do bloco
- **Clonar**: Botão de duplicar para copiar bloco
- **Deletar**: Pede confirmação antes de apagar
- **Colapsar**: Clique no título do bloco para minimizar

## 🎨 Renderizando no Frontend

### Opção 1: Componente Blade (Recomendado)

```blade
{{-- Em sua view --}}
<x-lesson-content :blocks="$lesson->content" />
```

Isso renderiza automaticamente todos os blocos com estilos bonitos!

### Opção 2: Loop Manual

```blade
@foreach($lesson->content as $block)
    @php
        $type = $block['type'];
        $data = $block['data'];
    @endphp
    
    @switch($type)
        @case('paragraph')
            <x-lesson-blocks.paragraph :content="$data['content']" />
            @break
        
        @case('heading')
            <x-lesson-blocks.heading 
                :level="$data['level']"
                :content="$data['content']"
            />
            @break
        
        {{-- outros blocos... --}}
    @endswitch
@endforeach
```

## 🔧 Adicionando Novos Blocos

### Passo 1: Adicionar no LessonResource.php

```php
Builder::make('content')
    ->blocks([
        // ... blocos existentes ...
        
        Block::make('meu_bloco')
            ->label('🆕 Meu Bloco')
            ->icon('heroicon-o-star')
            ->schema([
                Forms\Components\TextInput::make('titulo')
                    ->label('Título')
                    ->required(),
                
                Forms\Components\Textarea::make('conteudo')
                    ->label('Conteúdo')
                    ->required(),
            ]),
    ])
```

### Passo 2: Criar View Blade

```blade
{{-- resources/views/components/lesson-blocks/meu-bloco.blade.php --}}
<div class="meu-bloco">
    <h3>{{ $titulo }}</h3>
    <p>{{ $conteudo }}</p>
</div>
```

### Passo 3: Adicionar no Switch

```blade
{{-- resources/views/components/lesson-content.blade.php --}}
@case('meu_bloco')
    <x-lesson-blocks.meu-bloco 
        :titulo="$data['titulo']"
        :conteudo="$data['conteudo']"
    />
    @break
```

## 📊 Estrutura de Dados (JSON)

Os blocos são salvos como JSON no banco:

```json
[
  {
    "type": "heading",
    "data": {
      "level": "h2",
      "content": "Introdução"
    }
  },
  {
    "type": "paragraph",
    "data": {
      "content": "<p>Texto do parágrafo...</p>"
    }
  },
  {
    "type": "image",
    "data": {
      "file": "lessons/images/2025/12/abc123.jpg",
      "alt": "Descrição",
      "caption": "Legenda",
      "alignment": "center"
    }
  }
]
```

## 🎨 Customizando Estilos

### Opção 1: Editar View Blade do Bloco

```blade
{{-- resources/views/components/lesson-blocks/paragraph.blade.php --}}
<div class="lesson-paragraph minha-classe-custom">
    {!! $content !!}
</div>

<style>
    .minha-classe-custom {
        /* Seus estilos */
    }
</style>
```

### Opção 2: CSS Global

```css
/* public/css/lesson.css */
.lesson-content {
    max-width: 800px;
    margin: 0 auto;
}

.lesson-heading.h2 {
    font-size: 2.5rem;
    color: #1e40af;
}
```

## 🐛 Troubleshooting

### Blocos não aparecem
1. Verifique se `$lesson->content` é array
2. Verifique estrutura JSON no banco
3. Limpe cache: `php artisan view:clear`

### Imagens não carregam
1. `php artisan storage:link`
2. Verifique permissões da pasta `storage/app/public`
3. Confirme que `file` contém caminho relativo

### LaTeX não renderiza
1. Verifique console do navegador (erros do KaTeX)
2. Confirme que KaTeX está carregado
3. Teste sintaxe em https://katex.org/

### Vídeo não funciona
1. Verifique URL do vídeo
2. Para YouTube: formato correto `watch?v=ID`
3. Para Vimeo: formato correto `vimeo.com/ID`

## 📚 Recursos

- [Filament Builder Docs](https://filamentphp.com/docs/forms/fields/builder)
- [Filament RichEditor Docs](https://filamentphp.com/docs/forms/fields/rich-editor)
- [KaTeX Supported Functions](https://katex.org/docs/supported.html)
- [Highlight.js Languages](https://highlightjs.org/static/demo/)

## 🎯 Próximos Passos

- [ ] Adicionar mais blocos (accordion, tabs, cards)
- [ ] Implementar templates de blocos
- [ ] Adicionar snippets de LaTeX
- [ ] Preview em tempo real
- [ ] Versionamento de conteúdo

