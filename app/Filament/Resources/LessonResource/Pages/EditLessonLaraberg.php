<?php

// plataforma/app/Filament/Resources/LessonResource/Pages/EditLessonLaraberg.php

namespace App\Filament\Resources\LessonResource\Pages;

use App\Domain\Lesson\Models\Lesson;
use App\Filament\Resources\LessonResource;
use App\Forms\Components\LarabergEditor;
use Illuminate\Support\Facades\Log;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

/**
 * Página de edição de lição usando Laraberg (Gutenberg)
 * 
 * Esta é uma versão alternativa usando o Laraberg ao invés do Builder customizado
 */
class EditLessonLaraberg extends EditRecord
{
    protected static string $resource = LessonResource::class;

    protected static string $view = 'filament.resources.lesson-resource.pages.edit-lesson-laraberg';

    protected static ?string $title = '';

    protected function mutateFormDataBeforeFill(array $data): array
    {
        Log::info('[Laraberg] mutateFormDataBeforeFill', [
            'id' => $data['id'] ?? null,
            'title' => $data['title'] ?? null,
            'slug' => $data['slug'] ?? null,
            'content_type' => gettype($data['content'] ?? null),
            'content_is_array' => is_array($data['content'] ?? null),
        ]);

        // Converter content de array de blocos para formato Gutenberg (JSON string)
        // O modelo retorna content como array, mas Laraberg precisa de JSON string
        if (isset($data['content'])) {
            if (is_array($data['content']) && !empty($data['content'])) {
                // Converter formato de blocos para formato Gutenberg
                $gutenbergBlocks = $this->convertBlocksToGutenberg($data['content']);
                $data['content'] = json_encode($gutenbergBlocks);
            } elseif (is_string($data['content']) && !empty($data['content'])) {
                // Já é string, manter (pode ser JSON do Gutenberg ou HTML)
                // Tentar decodificar para verificar se é JSON válido
                $decoded = json_decode($data['content'], true);
                if (!is_array($decoded)) {
                    // Não é JSON válido, pode ser HTML ou vazio
                    $data['content'] = '';
                }
            } else {
                $data['content'] = '';
            }
        } else {
            $data['content'] = '';
        }
        
        return $data;
    }
    
    /**
     * Converte blocos do sistema para o formato Gutenberg
     * 
     * Formato Sistema: [{type: 'paragraph', data: {...}}]
     * Formato Gutenberg: [{blockName: 'core/paragraph', attrs: {...}, innerBlocks: [...]}]
     */
    protected function convertBlocksToGutenberg(array $blocks): array
    {
        $gutenbergBlocks = [];
        
        foreach ($blocks as $block) {
            if (!isset($block['type']) || empty($block['type'])) {
                continue;
            }
            
            $type = $block['type'];
            $blockData = $block['data'] ?? [];
            $attrs = $blockData['attrs'] ?? [];
            $innerBlocks = $blockData['innerBlocks'] ?? [];
            $innerContent = $blockData['innerContent'] ?? [];
            
            // Adicionar prefixo 'core/' ao tipo
            $blockName = 'core/' . $type;
            
            // Construir bloco Gutenberg
            $gutenbergBlock = [
                'blockName' => $blockName,
                'attrs' => $attrs,
            ];
            
            // Se tiver innerBlocks, processar recursivamente
            if (!empty($innerBlocks)) {
                $gutenbergBlock['innerBlocks'] = $this->convertBlocksToGutenberg($innerBlocks);
            }
            
            // Se tiver innerContent, adicionar
            if (!empty($innerContent)) {
                $gutenbergBlock['innerContent'] = $innerContent;
            }
            
            $gutenbergBlocks[] = $gutenbergBlock;
        }
        
        return $gutenbergBlocks;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Título da lição
                TextInput::make('title')
                    ->label('Título da Lição')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Atualizar slug quando título mudar
                        if (!empty($state)) {
                            $set('slug', Str::slug($state));
                        }
                    }),
                
                // Slug (hidden, atualizado pelo título)
                Hidden::make('slug'),
                
                // Editor Laraberg (Gutenberg)
                LarabergEditor::make('content')
                    ->label('Conteúdo')
                    ->options([
                        'height' => '600px',
                    ])
                    ->columnSpanFull(),
            ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Converter content do Gutenberg (JSON string) para array de blocos
        // O modelo Lesson espera content como array (será convertido para JSON no banco)
        if (isset($data['content'])) {
            $content = $data['content'];
            
            // Se for string (JSON do Gutenberg), decodificar e converter
            if (is_string($content) && !empty($content)) {
                $decoded = json_decode($content, true);
                
                if (is_array($decoded)) {
                    // Converter formato Gutenberg para formato de blocos do sistema
                    $data['content'] = $this->convertGutenbergToBlocks($decoded);
                } else {
                    // Se não for JSON válido, array vazio
                    $data['content'] = [];
                }
            } elseif (is_array($content)) {
                // Verificar se está no formato Gutenberg (tem 'blockName') ou já é formato de blocos
                if (!empty($content) && isset($content[0]) && isset($content[0]['blockName'])) {
                    // Formato Gutenberg, converter
                    $data['content'] = $this->convertGutenbergToBlocks($content);
                } else {
                    // Já está no formato de blocos, manter
                    $data['content'] = $content;
                }
            } else {
                $data['content'] = [];
            }
        } else {
            $data['content'] = [];
        }

        // Atualizar slug se título mudou
        if (isset($data['title']) && !empty($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        Log::info('[Laraberg] mutateFormDataBeforeSave', [
            'id' => $data['id'] ?? null,
            'title' => $data['title'] ?? null,
            'slug' => $data['slug'] ?? null,
            'content_type' => gettype($data['content'] ?? null),
            'content_is_array' => is_array($data['content'] ?? null),
            'content_count' => is_array($data['content']) ? count($data['content']) : 0,
            'content_sample' => substr(json_encode($data['content'] ?? ''), 0, 500),
        ]);

        return $data;
    }
    
    /**
     * Converte blocos do Gutenberg para o formato de blocos do sistema
     * 
     * Formato Gutenberg: [{blockName: 'core/paragraph', attrs: {...}, innerBlocks: [...]}]
     * Formato Sistema: [{type: 'paragraph', data: {...}}]
     */
    protected function convertGutenbergToBlocks(array $gutenbergBlocks): array
    {
        $blocks = [];
        
        foreach ($gutenbergBlocks as $gutenbergBlock) {
            if (!isset($gutenbergBlock['blockName']) || empty($gutenbergBlock['blockName'])) {
                continue;
            }
            
            $blockName = $gutenbergBlock['blockName'];
            $attrs = $gutenbergBlock['attrs'] ?? [];
            $innerContent = $gutenbergBlock['innerContent'] ?? [];
            $innerBlocks = $gutenbergBlock['innerBlocks'] ?? [];
            
            // Extrair tipo do bloco (remover prefixo 'core/')
            $type = str_replace('core/', '', $blockName);
            
            // Converter atributos e conteúdo para o formato de data
            $blockData = [
                'attrs' => $attrs,
            ];
            
            // Se tiver innerBlocks, processar recursivamente
            if (!empty($innerBlocks)) {
                $blockData['innerBlocks'] = $this->convertGutenbergToBlocks($innerBlocks);
            }
            
            // Se tiver innerContent (HTML), adicionar
            if (!empty($innerContent)) {
                $blockData['innerContent'] = $innerContent;
            }
            
            // Adicionar ao array de blocos
            $blocks[] = [
                'type' => $type,
                'data' => $blockData,
            ];
        }
        
        return $blocks;
    }
    
    protected function getRedirectUrl(): ?string
    {
        // Não redireciona após salvar, mantém na página do editor
        return null;
    }
    
    protected function getSavedNotification(): ?\Filament\Notifications\Notification
    {
        return \Filament\Notifications\Notification::make()
            ->title('Lição salva com sucesso!')
            ->success();
    }
    
    /**
     * Header actions - adicionar botão voltar e salvar
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn () => LessonResource::getUrl('index'))
                ->extraAttributes(['class' => 'laraberg-back-icon', 'title' => 'Voltar para lista de lições']),
            
            Actions\Action::make('save')
                ->label('Salvar alterações')
                ->icon('heroicon-o-check')
                ->color('primary')
                ->requiresConfirmation(false)
                ->action(function () {
                    Log::info('[Laraberg] Botão salvar clicado - iniciando save');
                    try {
                        $this->save();
                        Log::info('[Laraberg] Save executado com sucesso');
                    } catch (\Exception $e) {
                        Log::error('[Laraberg] Erro ao salvar', [
                            'message' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                        ]);
                        throw $e;
                    }
                })
                ->keyBindings(['mod+s']),
        ];
    }
    
    /**
     * Sobrescrever método save para adicionar logs
     */
    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        Log::info('[Laraberg] Método save chamado', [
            'shouldRedirect' => $shouldRedirect,
            'shouldSendSavedNotification' => $shouldSendSavedNotification,
            'record_id' => $this->record->id ?? 'new',
        ]);
        
        try {
            parent::save($shouldRedirect, $shouldSendSavedNotification);
            Log::info('[Laraberg] Parent save executado com sucesso');
        } catch (\Exception $e) {
            Log::error('[Laraberg] Erro no parent save', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}

