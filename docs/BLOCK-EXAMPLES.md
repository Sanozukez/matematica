# 💡 Exemplos Práticos - Sistema de Blocos

## 🎯 Exemplo 1: Criando um Bloco Simples

### Bloco de Nota (Post-it)

```php
<?php
// app/Domain/Lesson/Blocks/NoteBlock.php

namespace App\Domain\Lesson\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;

/**
 * Bloco de Nota
 * 
 * Post-it colorido para destacar informações importantes
 */
class NoteBlock extends AbstractBlock
{
    protected string $name = 'note';
    protected string $label = '📌 Nota';
    protected string $icon = 'heroicon-o-clipboard-document';

    public function make(): Block
    {
        return $this->createBlock();
    }

    protected function getSchema(): array
    {
        return [
            Select::make('color')
                ->label('Cor da Nota')
                ->options([
                    'yellow' => '🟡 Amarelo',
                    'blue' => '🔵 Azul',
                    'green' => '🟢 Verde',
                    'pink' => '🩷 Rosa',
                ])
                ->default('yellow')
                ->required(),
            
            Textarea::make('content')
                ->label('Texto da Nota')
                ->required()
                ->rows(4)
                ->placeholder('Digite sua nota aqui...')
                ->columnSpanFull(),
        ];
    }
}
```

**Registrar:**
```php
// app/Domain/Lesson/Services/BlockRegistry.php
private function registerDefaultBlocks(): void
{
    // ... outros blocos ...
    $this->register(new NoteBlock());
}
```

---

## 🎯 Exemplo 2: Bloco com Validação Customizada

### Bloco de Quiz Inline

```php
<?php

namespace App\Domain\Lesson\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Radio;

class QuizInlineBlock extends AbstractBlock
{
    protected string $name = 'quiz_inline';
    protected string $label = '❓ Quiz Rápido';
    protected string $icon = 'heroicon-o-question-mark-circle';

    public function make(): Block
    {
        return $this->createBlock();
    }

    protected function getSchema(): array
    {
        return [
            TextInput::make('question')
                ->label('Pergunta')
                ->required()
                ->placeholder('Quanto é 2 + 2?'),
            
            Repeater::make('options')
                ->label('Opções de Resposta')
                ->schema([
                    TextInput::make('text')
                        ->label('Opção')
                        ->required(),
                ])
                ->minItems(2)
                ->maxItems(4)
                ->defaultItems(3)
                ->collapsible(),
            
            Radio::make('correct_answer')
                ->label('Resposta Correta')
                ->options(fn ($get) => 
                    collect($get('options'))
                        ->pluck('text', 'text')
                        ->toArray()
                )
                ->required(),
        ];
    }
}
```

---

## 🎯 Exemplo 3: Bloco com Upload e Preview

### Bloco de Documento PDF

```php
<?php

namespace App\Domain\Lesson\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;

class DocumentBlock extends AbstractBlock
{
    protected string $name = 'document';
    protected string $label = '📄 Documento PDF';
    protected string $icon = 'heroicon-o-document-text';

    public function make(): Block
    {
        return $this->createBlock();
    }

    protected function getSchema(): array
    {
        return [
            FileUpload::make('file')
                ->label('Arquivo PDF')
                ->acceptedFileTypes(['application/pdf'])
                ->directory('lessons/documents')
                ->maxSize(10240) // 10MB
                ->required()
                ->downloadable()
                ->previewable()
                ->columnSpanFull(),
            
            TextInput::make('title')
                ->label('Título do Documento')
                ->required()
                ->placeholder('Ex: Lista de Exercícios'),
            
            Checkbox::make('allow_download')
                ->label('Permitir Download')
                ->default(true)
                ->helperText('Alunos podem baixar o PDF'),
        ];
    }
}
```

---

## 🎯 Exemplo 4: Usando BlockRegistry em Contextos Diferentes

### Editor Simplificado (Apenas texto básico)

```php
<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Domain\Lesson\Services\BlockRegistry;
use App\Domain\Lesson\Services\LessonEditorService;
use Filament\Forms\Form;

class EditArticle extends EditRecord
{
    public function form(Form $form): Form
    {
        $blockRegistry = new BlockRegistry();
        $editorService = new LessonEditorService($blockRegistry);
        
        return $form->schema([
            // Apenas blocos de texto
            $editorService->createBuilderWithBlocks(
                ['paragraph', 'heading', 'quote'],
                [
                    'label' => 'Conteúdo do Artigo',
                    'minItems' => 1,
                ]
            ),
        ]);
    }
}
```

### Editor Multimídia (Sem código/fórmulas)

```php
// Todos os blocos exceto técnicos
$blockRegistry = new BlockRegistry();
$editorService = new LessonEditorService($blockRegistry);

$builder = Builder::make('content')
    ->blocks(
        $blockRegistry->except(['code', 'latex', 'table'])
    );
```

---

## 🎯 Exemplo 5: Estendendo Bloco Existente

### ImageBlock com Galeria

```php
<?php

namespace App\Domain\Lesson\Blocks;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;

class ImageGalleryBlock extends ImageBlock
{
    protected string $name = 'image_gallery';
    protected string $label = '🖼️ Galeria de Imagens';

    protected function getSchema(): array
    {
        // Pegar schema do pai
        $schema = parent::getSchema();
        
        // Adicionar campos extras
        $schema[] = Select::make('columns')
            ->label('Colunas')
            ->options([
                '1' => '1 coluna',
                '2' => '2 colunas',
                '3' => '3 colunas',
                '4' => '4 colunas',
            ])
            ->default('3');
        
        $schema[] = Checkbox::make('lightbox')
            ->label('Abrir em Lightbox')
            ->default(true);
        
        return $schema;
    }
}
```

---

## 🎯 Exemplo 6: Bloco com Lógica Condicional

### Bloco de Exercício com Solução

```php
<?php

namespace App\Domain\Lesson\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;

class ExerciseBlock extends AbstractBlock
{
    protected string $name = 'exercise';
    protected string $label = '✏️ Exercício';
    protected string $icon = 'heroicon-o-academic-cap';

    public function make(): Block
    {
        return $this->createBlock();
    }

    protected function getSchema(): array
    {
        return [
            Textarea::make('question')
                ->label('Enunciado do Exercício')
                ->required()
                ->rows(4),
            
            Toggle::make('has_solution')
                ->label('Incluir Solução')
                ->live(),
            
            // Mostrar campo de solução apenas se toggle ativado
            RichEditor::make('solution')
                ->label('Solução')
                ->toolbarButtons(['bold', 'italic', 'bulletList'])
                ->visible(fn ($get) => $get('has_solution'))
                ->columnSpanFull(),
            
            Toggle::make('show_solution_immediately')
                ->label('Mostrar Solução Imediatamente')
                ->visible(fn ($get) => $get('has_solution'))
                ->helperText('Se desativado, aluno precisa clicar para ver'),
        ];
    }
}
```

---

## 🎯 Exemplo 7: Service Provider para Blocos Customizados

### Registrar Blocos Automaticamente

```php
<?php
// app/Providers/LessonBlockServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Lesson\Services\BlockRegistry;
use App\Domain\Lesson\Blocks\Custom\MeuBlocoCustomizado;

class LessonBlockServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(BlockRegistry::class, function ($app) {
            $registry = new BlockRegistry();
            
            // Registrar blocos customizados do projeto
            $registry->register(new MeuBlocoCustomizado());
            
            return $registry;
        });
    }
}
```

**Registrar no config/app.php:**
```php
'providers' => [
    // ...
    App\Providers\LessonBlockServiceProvider::class,
],
```

**Usar injeção de dependência:**
```php
public function form(Form $form, BlockRegistry $blockRegistry): Form
{
    $editorService = new LessonEditorService($blockRegistry);
    
    return $form->schema([
        $editorService->createBuilder(),
    ]);
}
```

---

## 🎯 Exemplo 8: Renderização no Frontend

### Component Blade para Blocos

```php
<?php
// app/View/Components/LessonBlock.php

namespace App\View\Components;

use Illuminate\View\Component;

class LessonBlock extends Component
{
    public function __construct(
        public string $type,
        public array $data
    ) {}

    public function render()
    {
        // Renderizar view específica para cada tipo
        return view("components.blocks.{$this->type}", [
            'data' => $this->data
        ]);
    }
}
```

**Blade Template:**
```blade
{{-- resources/views/components/blocks/paragraph.blade.php --}}
<div class="prose prose-lg">
    {!! $data['content'] !!}
</div>

{{-- resources/views/components/blocks/heading.blade.php --}}
<{{ $data['level'] }} 
    @if($data['color']) style="color: {{ $data['color'] }}" @endif
>
    {{ $data['content'] }}
</{{ $data['level'] }}>

{{-- resources/views/components/blocks/alert.blade.php --}}
<div class="alert alert-{{ $data['type'] }}">
    @if($data['title'])
        <strong>{{ $data['title'] }}</strong>
    @endif
    <p>{{ $data['content'] }}</p>
</div>
```

**Usar no frontend:**
```blade
{{-- resources/views/lessons/show.blade.php --}}
@foreach($lesson->content as $block)
    <x-lesson-block 
        :type="$block['type']" 
        :data="$block['data']" 
    />
@endforeach
```

---

## 🎯 Exemplo 9: Testes Unitários

### Testar Blocos

```php
<?php
// tests/Unit/Lesson/Blocks/ParagraphBlockTest.php

namespace Tests\Unit\Lesson\Blocks;

use Tests\TestCase;
use App\Domain\Lesson\Blocks\ParagraphBlock;
use Filament\Forms\Components\Builder\Block;

class ParagraphBlockTest extends TestCase
{
    private ParagraphBlock $block;

    protected function setUp(): void
    {
        parent::setUp();
        $this->block = new ParagraphBlock();
    }

    public function test_has_correct_name()
    {
        $this->assertEquals('paragraph', $this->block->getName());
    }

    public function test_has_correct_label()
    {
        $this->assertEquals('📝 Parágrafo', $this->block->getLabel());
    }

    public function test_creates_valid_filament_block()
    {
        $filamentBlock = $this->block->make();
        
        $this->assertInstanceOf(Block::class, $filamentBlock);
    }

    public function test_schema_has_content_field()
    {
        $block = $this->block->make();
        $schema = $block->getChildComponents();
        
        $this->assertNotEmpty($schema);
        $this->assertEquals('content', $schema[0]->getName());
    }
}
```

### Testar BlockRegistry

```php
<?php

namespace Tests\Unit\Lesson\Services;

use Tests\TestCase;
use App\Domain\Lesson\Services\BlockRegistry;

class BlockRegistryTest extends TestCase
{
    private BlockRegistry $registry;

    protected function setUp(): void
    {
        parent::setUp();
        $this->registry = new BlockRegistry();
    }

    public function test_registers_default_blocks()
    {
        $blocks = $this->registry->all();
        
        $this->assertNotEmpty($blocks);
        $this->assertArrayHasKey('paragraph', $blocks);
        $this->assertArrayHasKey('heading', $blocks);
    }

    public function test_can_get_specific_block()
    {
        $block = $this->registry->get('paragraph');
        
        $this->assertNotNull($block);
        $this->assertEquals('paragraph', $block->getName());
    }

    public function test_only_method_filters_correctly()
    {
        $blocks = $this->registry->only(['paragraph', 'heading']);
        
        $this->assertCount(2, $blocks);
    }

    public function test_except_method_excludes_correctly()
    {
        $allCount = count($this->registry->all());
        $blocks = $this->registry->except(['paragraph']);
        
        $this->assertCount($allCount - 1, $blocks);
    }
}
```

---

## 🎉 Resumo

Estes exemplos mostram:

1. ✅ Como criar blocos simples e complexos
2. ✅ Como estender blocos existentes
3. ✅ Como usar BlockRegistry em diferentes contextos
4. ✅ Como renderizar blocos no frontend
5. ✅ Como testar blocos unitariamente

**Explore, experimente e crie seus próprios blocos!** 🚀
