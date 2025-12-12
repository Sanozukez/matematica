# 📝 Guia Completo: TipTap Editor

## 🎯 Visão Geral

O TipTap Editor foi implementado para substituir o Editor.js, oferecendo uma experiência de edição profissional similar ao WordPress/Notion.

### ✅ Vantagens do TipTap

- **Barra de ferramentas rica**: Como Word/Google Docs
- **Formatação completa**: Cores, tamanhos, alinhamento
- **Imagens**: Upload direto com preview
- **LaTeX/Matemática**: Suporte nativo (em desenvolvimento)
- **Tabelas**: Criação e edição intuitiva
- **Código**: Blocos de código com syntax highlighting
- **JSON estruturado**: Fácil de manipular e migrar

## 🚀 Como Usar

### 1. Criando uma Lição

1. Acesse **Admin → Lições → Criar**
2. Preencha:
   - **Módulo**: Selecione o módulo
   - **Título**: Nome da lição
   - **Tipo**: Escolha "Texto/Conteúdo"
3. Clique em **Criar**

### 2. Editando Conteúdo

#### Modo Normal (Configurações)
- Acesse **Admin → Lições → Editar**
- Permite configurar: duração, ordem, status, tipo
- Editor integrado na página

#### Modo Fullscreen (Recomendado) ⭐
- Na lista de lições, clique no botão **"Editor"** (ícone de lápis)
- Ou acesse diretamente: `/admin/lessons/{id}/fullscreen`
- **Vantagens**:
  - Tela cheia para foco total
  - Sidebar com informações e atalhos
  - Salvar com `Ctrl+S`
  - Interface limpa e profissional

### 3. Ferramentas Disponíveis

#### Formatação de Texto
- **Negrito**: `Ctrl+B` ou botão
- **Itálico**: `Ctrl+I` ou botão
- **Sublinhado**: Botão na barra
- **Riscado**: Botão na barra
- **Sobrescrito**: Para expoentes (x²)
- **Subscrito**: Para índices (H₂O)

#### Cores e Destaque
- **Cor do texto**: Paleta com 8 cores predefinidas
- **Destaque**: Marca-texto amarelo/colorido

#### Alinhamento
- Esquerda (padrão)
- Centro
- Direita

#### Blocos Especiais
- **Títulos**: H2, H3, H4
- **Listas**: Numeradas, com marcadores
- **Citações**: Blockquote
- **Código**: Blocos de código
- **Tabelas**: Criar e editar tabelas
- **Linha horizontal**: Separador

#### Mídia
- **Imagens**: Upload direto ou URL
- **Vídeos**: Embed (em desenvolvimento)

## 🎨 Perfis de Ferramentas

O TipTap usa "perfis" para definir quais ferramentas estão disponíveis:

### Perfil `lesson` (Usado nas Lições)
```php
'lesson' => [
    'heading',           // Títulos
    'bullet-list',       // Lista com marcadores
    'ordered-list',      // Lista numerada
    'blockquote',        // Citação
    'hr',                // Linha horizontal
    '|',                 // Separador visual
    'bold',              // Negrito
    'italic',            // Itálico
    'strike',            // Riscado
    'underline',         // Sublinhado
    'superscript',       // Sobrescrito
    'subscript',         // Subscrito
    'color',             // Cor do texto
    'highlight',         // Destaque
    '|',
    'align-left',        // Alinhar esquerda
    'align-center',      // Alinhar centro
    'align-right',       // Alinhar direita
    '|',
    'link',              // Links
    'media',             // Imagens/vídeos
    'table',             // Tabelas
    'code-block',        // Blocos de código
    '|',
    'source',            // Ver código HTML
],
```

## 🎨 Cores Predefinidas

As cores foram escolhidas para destacar conceitos educacionais:

| Cor | Hex | Uso Sugerido |
|-----|-----|--------------|
| 🔵 Primary | `#3b82f6` | Conceitos principais |
| 🟢 Success | `#10b981` | Respostas corretas, exemplos positivos |
| 🟡 Warning | `#f59e0b` | Avisos, atenção |
| 🔴 Danger | `#ef4444` | Erros, conceitos críticos |
| 🔷 Info | `#06b6d4` | Informações adicionais |
| 🟣 Purple | `#8b5cf6` | Definições, teoremas |
| 🌸 Pink | `#ec4899` | Exemplos, exercícios |
| ⚫ Gray | `#6b7280` | Notas, observações |

## 📷 Upload de Imagens

### Como Funciona

1. **Upload Direto**:
   - Clique no botão "Imagem" na barra
   - Selecione arquivo do computador
   - Imagem é salva em `/storage/lessons/images/YYYY/MM/`

2. **Por URL**:
   - Cole URL de imagem externa
   - Sistema baixa e salva localmente
   - **Regra de Ouro**: Nunca hotlink externo!

### Configurações

```php
// config/filament-tiptap-editor.php
'accepted_file_types' => ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'],
'disk' => 'public',
'directory' => 'lessons/images',
'max_file_size' => 10240, // 10MB
```

### Migração para Bunny.net

Quando ativar o Bunny.net:

1. Configure `.env`:
```env
BUNNY_STORAGE_ZONE=your-zone
BUNNY_STORAGE_API_KEY=your-key
BUNNY_CDN_URL=https://your-cdn.b-cdn.net
```

2. Atualize `config/filament-tiptap-editor.php`:
```php
'disk' => 'bunnycdn',
```

3. Pronto! Uploads irão automaticamente para o Bunny.net

## 🧮 LaTeX/Matemática (Em Desenvolvimento)

### Status Atual
- ✅ KaTeX carregado
- ✅ Estilos preparados
- ⏳ Extensão customizada em desenvolvimento
- ⏳ Botão na barra de ferramentas

### Como Será (Preview)

```latex
// Inline: $x^2 + y^2 = z^2$
// Block:
$$
\int_{a}^{b} f(x) dx = F(b) - F(a)
$$
```

### Exemplos Comuns

| Descrição | LaTeX |
|-----------|-------|
| Fração | `\frac{a}{b}` |
| Raiz | `\sqrt{x}` |
| Potência | `x^{2}` |
| Subscrito | `x_{i}` |
| Somatório | `\sum_{i=1}^{n} x_i` |
| Integral | `\int_{a}^{b} f(x) dx` |
| Limite | `\lim_{x \to \infty} f(x)` |
| Matriz | `\begin{pmatrix} a & b \\ c & d \end{pmatrix}` |

## ⌨️ Atalhos de Teclado

### Formatação
- `Ctrl+B`: Negrito
- `Ctrl+I`: Itálico
- `Ctrl+U`: Sublinhado
- `Ctrl+Shift+X`: Riscado
- `Ctrl+K`: Inserir link

### Blocos
- `Ctrl+Alt+1`: Título H2
- `Ctrl+Alt+2`: Título H3
- `Ctrl+Shift+7`: Lista numerada
- `Ctrl+Shift+8`: Lista com marcadores
- `Ctrl+Shift+9`: Citação

### Edição
- `Ctrl+S`: Salvar (modo fullscreen)
- `Ctrl+Z`: Desfazer
- `Ctrl+Y`: Refazer
- `Ctrl+A`: Selecionar tudo

## 🔧 Personalização

### Adicionar Nova Cor

```php
// config/filament-tiptap-editor.php
'preset_colors' => [
    'custom' => '#ff6b6b', // Sua cor
],
```

### Criar Novo Perfil

```php
'profiles' => [
    'meu_perfil' => [
        'bold', 'italic', 'link', // Ferramentas desejadas
    ],
],
```

### Usar no Resource

```php
TiptapEditor::make('content')
    ->profile('meu_perfil')
```

## 📊 Formato de Dados

### JSON Estruturado

O TipTap salva em JSON (não HTML):

```json
{
  "type": "doc",
  "content": [
    {
      "type": "heading",
      "attrs": { "level": 2 },
      "content": [
        { "type": "text", "text": "Título da Seção" }
      ]
    },
    {
      "type": "paragraph",
      "content": [
        { "type": "text", "text": "Parágrafo normal." }
      ]
    }
  ]
}
```

### Vantagens

- ✅ Estruturado e editável
- ✅ Fácil de migrar
- ✅ Pode gerar HTML, Markdown, etc.
- ✅ Versionamento simples

### Renderizar no Frontend

```php
// Opção 1: Usar o pacote tiptap-php
use Tiptap\Editor;

$html = (new Editor)
    ->setContent($lesson->content)
    ->getHTML();

// Opção 2: Criar view Blade customizada
@foreach($lesson->content['content'] as $block)
    @include('components.tiptap-block', ['block' => $block])
@endforeach
```

## 🐛 Troubleshooting

### Editor não carrega
1. Limpe o cache: `php artisan view:clear`
2. Verifique console do navegador
3. Confirme que o pacote está instalado: `composer show awcodes/filament-tiptap-editor`

### Imagens não aparecem
1. Verifique permissões: `php artisan storage:link`
2. Confirme disco público: `config/filesystems.php`
3. Teste upload manual

### Formatação não salva
1. Verifique coluna no banco: deve ser `json` ou `longtext`
2. Confirme output: `config/filament-tiptap-editor.php` → `'output' => TiptapOutput::Json`

## 📚 Recursos Adicionais

- [Documentação Oficial TipTap](https://tiptap.dev)
- [Filament TipTap Editor](https://github.com/awcodes/filament-tiptap-editor)
- [KaTeX Documentation](https://katex.org/docs/supported.html)

## 🎯 Próximos Passos

- [ ] Finalizar extensão LaTeX
- [ ] Adicionar templates de blocos
- [ ] Criar snippets educacionais
- [ ] Implementar colaboração em tempo real
- [ ] Adicionar histórico de versões

