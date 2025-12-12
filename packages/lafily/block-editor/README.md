# 🚀 Lafily Block Editor

**Modern block-based content editor for Laravel & Filament**

Inspired by WordPress Gutenberg, built with Laravel and Filament for maximum flexibility and extensibility.

## ✨ Features

- 🧱 **Modular Block System** - Each block type is a separate class (SRP)
- 🎨 **Modern UI** - Three-column layout with inserter, canvas, and settings
- 📱 **Responsive** - Works on desktop, tablet, and mobile
- 🔧 **Extensible** - Easy to add custom blocks
- 🎯 **Filament Integration** - Native integration with FilamentPHP
- ⚡ **Performance** - Optimized rendering and state management

## 📦 Installation

```bash
composer require lafily/block-editor
```

## 🎯 Usage

```php
use Lafily\BlockEditor\Services\BlockRegistry;
use Lafily\BlockEditor\Services\EditorService;

$blockRegistry = new BlockRegistry();
$editorService = new EditorService($blockRegistry);

// Create builder with all blocks
$builder = $editorService->createBuilder();

// Or specific blocks only
$builder = $editorService->createBuilderWithBlocks([
    'paragraph',
    'heading',
    'image',
]);
```

## 🧱 Available Blocks

- **Paragraph** - Rich text content
- **Heading** - H1-H6 headings
- **Image** - Images with captions
- **Video** - Video embeds
- **Code** - Syntax-highlighted code
- **Quote** - Blockquotes
- **List** - Ordered/unordered lists
- **Table** - Data tables
- **Divider** - Horizontal rules
- **Alert** - Info/warning/error boxes
- **LaTeX** - Mathematical equations

## 🔧 Creating Custom Blocks

```php
namespace App\Blocks;

use Lafily\BlockEditor\Blocks\AbstractBlock;
use Filament\Forms\Components\TextInput;

class CustomBlock extends AbstractBlock
{
    public function getType(): string
    {
        return 'custom';
    }

    public function getLabel(): string
    {
        return 'Custom Block';
    }

    public function getIcon(): string
    {
        return 'heroicon-o-puzzle-piece';
    }

    public function getSchema(): array
    {
        return [
            TextInput::make('content')
                ->label('Content')
                ->required(),
        ];
    }
}
```

## 📄 License

MIT License - feel free to use in your projects!

---

**Built with ❤️ using Laravel, Filament, and modern web technologies**
