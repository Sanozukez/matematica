# 🏗️ Arquitetura Refatorada do Editor de Lições

## 📋 Visão Geral

O editor de lições foi completamente refatorado seguindo os princípios **SOLID**, especialmente o **Single Responsibility Principle (SRP)**. Cada bloco de conteúdo agora tem sua própria classe, facilitando manutenção, testes e expansão.

## ✅ O que foi feito

### 1. **Remoção do Laraberg**
- ❌ Removido `van-ons/laraberg` do `composer.json`
- ✅ Mantido apenas `awcodes/filament-tiptap-editor` (já estava em uso)

### 2. **Nova Estrutura de Blocos**

```
app/Domain/Lesson/
├── Blocks/                          # 🆕 Sistema modular de blocos
│   ├── BlockContract.php           # Interface para todos os blocos
│   ├── AbstractBlock.php           # Classe base com lógica comum
│   ├── ParagraphBlock.php          # Bloco de parágrafo (TipTap)
│   ├── HeadingBlock.php            # Bloco de título (H2, H3, H4)
│   ├── ImageBlock.php              # Bloco de imagem
│   ├── VideoBlock.php              # Bloco de vídeo
│   ├── CodeBlock.php               # Bloco de código
│   ├── QuoteBlock.php              # Bloco de citação
│   ├── AlertBlock.php              # Bloco de alerta
│   ├── ListBlock.php               # Bloco de lista
│   ├── LatexBlock.php              # Bloco de fórmula LaTeX
│   ├── DividerBlock.php            # Bloco divisor
│   └── TableBlock.php              # Bloco de tabela
├── Services/
│   ├── BlockRegistry.php           # 🆕 Registro central de blocos
│   └── LessonEditorService.php     # 🆕 Serviço de configuração do editor
└── Models/
    └── Lesson.php
```

## 🎯 Princípios Aplicados

### **Single Responsibility Principle (SRP)**
- Cada bloco tem **uma única responsabilidade**: definir seu próprio schema
- `BlockRegistry`: responsável apenas por **registrar e fornecer blocos**
- `LessonEditorService`: responsável apenas por **configurar o editor**

### **Open/Closed Principle**
- Sistema **aberto para extensão**: adicione novos blocos facilmente
- **Fechado para modificação**: não precisa alterar código existente

### **Dependency Inversion**
- Dependências baseadas em **abstrações** (`BlockContract`)
- Não dependemos de implementações concretas

## 📦 Como Usar

### **1. Usando os Blocos no Editor Fullscreen**

```php
// EditLessonFullscreen.php
use App\Domain\Lesson\Services\BlockRegistry;
use App\Domain\Lesson\Services\LessonEditorService;

public function form(Form $form): Form
{
    $blockRegistry = new BlockRegistry();
    $editorService = new LessonEditorService($blockRegistry);
    
    return $form->schema([
        // Seu formulário...
        
        // Builder com todos os blocos
        $editorService->createBuilder([
            'label' => '',
            'addActionLabel' => '➕ Adicionar Bloco',
            'collapsible' => true,
            'cloneable' => true,
            'reorderable' => true,
            'minItems' => 0,
            'confirmDelete' => true,
        ]),
    ]);
}
```

### **2. Usando Apenas Blocos Específicos**

```php
// Se quiser apenas alguns blocos
$editorService->createBuilderWithBlocks(
    ['paragraph', 'heading', 'image'],
    ['label' => 'Conteúdo Simples']
);
```

### **3. Adicionando um Novo Bloco**

#### Passo 1: Criar a classe do bloco

```php
<?php
// app/Domain/Lesson/Blocks/AudioBlock.php

namespace App\Domain\Lesson\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;

class AudioBlock extends AbstractBlock
{
    protected string $name = 'audio';
    protected string $label = '🎵 Áudio';
    protected string $icon = 'heroicon-o-speaker-wave';

    public function make(): Block
    {
        return $this->createBlock();
    }

    protected function getSchema(): array
    {
        return [
            FileUpload::make('file')
                ->label('Arquivo de Áudio')
                ->acceptedFileTypes(['audio/*'])
                ->directory('lessons/audio')
                ->required(),
            
            TextInput::make('title')
                ->label('Título do Áudio')
                ->placeholder('Ex: Explicação sobre X'),
        ];
    }
}
```

#### Passo 2: Registrar o bloco

```php
// app/Domain/Lesson/Services/BlockRegistry.php

private function registerDefaultBlocks(): void
{
    // ... blocos existentes ...
    $this->register(new AudioBlock()); // 🆕 Adicione aqui
}
```

**Pronto!** O novo bloco já está disponível no editor.

## 🔧 Métodos Úteis do BlockRegistry

### `all()` - Obter todos os blocos
```php
$blockRegistry = new BlockRegistry();
$todosOsBlocos = $blockRegistry->all();
```

### `get($name)` - Obter bloco específico
```php
$paragrafoBlock = $blockRegistry->get('paragraph');
```

### `only($names)` - Filtrar blocos específicos
```php
// Apenas blocos básicos
$basicBlocks = $blockRegistry->only([
    'paragraph',
    'heading',
    'image',
]);
```

### `except($names)` - Excluir blocos
```php
// Todos exceto código e LaTeX
$simpleBlocks = $blockRegistry->except([
    'code',
    'latex',
]);
```

### `register($block)` - Adicionar bloco customizado
```php
$blockRegistry->register(new MeuBlocoCustomizado());
```

### `unregister($name)` - Remover bloco
```php
$blockRegistry->unregister('video');
```

## 🎨 Personalizando Blocos Existentes

Se você quiser modificar um bloco existente, **não edite a classe original**. Em vez disso, **estenda** a classe:

```php
<?php

namespace App\Domain\Lesson\Blocks;

class ImageBlockExtended extends ImageBlock
{
    protected function getSchema(): array
    {
        $schema = parent::getSchema();
        
        // Adicionar campo extra
        $schema[] = TextInput::make('photographer')
            ->label('Fotógrafo')
            ->placeholder('Nome do fotógrafo');
        
        return $schema;
    }
}
```

Depois registre sua versão estendida:

```php
$blockRegistry->unregister('image');
$blockRegistry->register(new ImageBlockExtended());
```

## 📊 Benefícios da Refatoração

### **Antes** ❌
- 🔴 Blocos definidos inline (600+ linhas duplicadas)
- 🔴 Difícil manutenção (alterar em 2 lugares)
- 🔴 Impossível testar blocos individualmente
- 🔴 Adicionar bloco = copiar/colar código
- 🔴 Violação de SRP (Resource faz muita coisa)

### **Depois** ✅
- 🟢 Cada bloco em sua própria classe (~50 linhas)
- 🟢 Manutenção centralizada (1 lugar por bloco)
- 🟢 Blocos testáveis unitariamente
- 🟢 Adicionar bloco = criar nova classe + 1 linha
- 🟢 Seguindo SOLID e boas práticas

## 🧪 Testando Blocos (Exemplo)

```php
<?php

namespace Tests\Unit\Lesson\Blocks;

use App\Domain\Lesson\Blocks\ParagraphBlock;
use Tests\TestCase;

class ParagraphBlockTest extends TestCase
{
    public function test_paragraph_block_has_correct_name()
    {
        $block = new ParagraphBlock();
        $this->assertEquals('paragraph', $block->getName());
    }
    
    public function test_paragraph_block_creates_valid_schema()
    {
        $block = new ParagraphBlock();
        $filamentBlock = $block->make();
        
        $this->assertInstanceOf(Block::class, $filamentBlock);
    }
}
```

## 🚀 Próximos Passos

### **Curto Prazo**
1. ✅ Remover código legado/comentado
2. ⏳ Testar todos os blocos no editor
3. ⏳ Adicionar validações específicas por bloco
4. ⏳ Criar testes unitários para cada bloco

### **Médio Prazo**
1. ⏳ Adicionar renderizadores específicos para cada bloco no frontend
2. ⏳ Implementar preview em tempo real
3. ⏳ Adicionar suporte para blocos customizados por módulo
4. ⏳ Sistema de templates de blocos

### **Longo Prazo**
1. ⏳ Editor colaborativo em tempo real
2. ⏳ Histórico de versões de conteúdo
3. ⏳ Biblioteca de blocos compartilhados
4. ⏳ IA para sugerir blocos

## 📝 Convenções de Código

### **Nome dos Blocos**
- Use nomes descritivos em inglês
- Sufixo `Block`: `ParagraphBlock`, `VideoBlock`
- Nome interno sem "Block": `'paragraph'`, `'video'`

### **Ícones**
- Use Heroicons: `heroicon-o-*`
- Escolha ícones intuitivos
- Seja consistente com a paleta existente

### **Labels**
- Use emojis para facilitar identificação visual
- Formato: `'🎥 Vídeo'`, `'📝 Parágrafo'`
- Português brasileiro no admin

## 🔍 Troubleshooting

### **Bloco não aparece no editor**
1. Verifique se foi registrado em `BlockRegistry::registerDefaultBlocks()`
2. Confirme que a classe implementa `BlockContract`
3. Verifique se o método `make()` retorna um `Block` válido

### **Erro ao salvar conteúdo**
1. Verifique se o schema do bloco tem todos os campos `required` preenchidos
2. Confirme que o campo `content` no model `Lesson` aceita array
3. Verifique logs em `storage/logs/laravel.log`

### **Bloco não renderiza no frontend**
1. Adicione renderização no componente `lesson-content.blade.php`
2. Verifique se o tipo do bloco está sendo salvo corretamente
3. Confirme estrutura JSON do conteúdo no banco

## 🤝 Contribuindo

Ao adicionar novos blocos:
1. **Crie a classe** estendendo `AbstractBlock`
2. **Registre no BlockRegistry**
3. **Adicione testes** em `tests/Unit/Lesson/Blocks/`
4. **Documente** uso e opções especiais
5. **Adicione renderização** no frontend

---

**Criado em:** 11 de Dezembro de 2025  
**Última Atualização:** 11 de Dezembro de 2025  
**Versão:** 2.0 (Refatoração Completa)
