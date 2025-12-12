<?php

namespace App\Filament\Resources\LessonResource\Pages;

use App\Domain\Lesson\Models\Lesson;
use App\Domain\Lesson\Services\BlockRegistry;
use App\Domain\Lesson\Services\LessonEditorService;
use App\Filament\Resources\LessonResource;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * Editor Fullscreen Refatorado
 * 
 * Usa o sistema de blocos modular via BlockRegistry.
 * Cada bloco está em sua própria classe, facilitando manutenção.
 */
class EditLessonFullscreen extends EditRecord
{
    protected static string $resource = LessonResource::class;

    protected static string $view = 'filament.resources.lesson-resource.pages.edit-lesson-fullscreen';

    protected static ?string $title = 'Editor de Lição';

    protected function mutateFormDataBeforeFill(array $data): array
    {
        Log::info('[Fullscreen Editor] mutateFormDataBeforeFill', [
            'id' => $data['id'] ?? null,
            'title' => $data['title'] ?? null,
            'content_type' => gettype($data['content'] ?? null),
            'content_is_array' => is_array($data['content'] ?? null),
        ]);
        
        // Parse content se for string JSON
        if (isset($data['content'])) {
            if (is_string($data['content'])) {
                $decoded = json_decode($data['content'], true);
                $data['content'] = is_array($decoded) ? $decoded : [];
            }
            if (!is_array($data['content'])) {
                $data['content'] = [];
            }
        }
        
        return $data;
    }

    public function form(Form $form): Form
    {
        Log::info('[Fullscreen Editor] form() method called');
        
        // Instanciar serviços
        $blockRegistry = new BlockRegistry();
        $editorService = new LessonEditorService($blockRegistry);
        
        return $form
            ->schema([
                // Título (visível, estilizado para parecer com WordPress)
                TextInput::make('title')
                    ->label('')
                    ->placeholder('Digite o título da lição...')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (!empty($state)) {
                            $set('slug', Str::slug($state));
                        }
                    })
                    ->extraAttributes([
                        'class' => 'lesson-title-input',
                        'style' => 'font-size: 2.5rem; font-weight: 600; border: none; padding: 0;',
                    ])
                    ->columnSpanFull(),
                
                // Slug (hidden, atualizado pelo título)
                Hidden::make('slug'),
                
                // Builder de Blocos usando BlockRegistry
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Visualizar')
                ->icon('heroicon-o-eye')
                ->url(function (Lesson $record): string {
                    // Se a rota pública não existir ainda, retorna permalink padrão
                    try {
                        return route('lessons.show', $record);
                    } catch (\Throwable $e) {
                        $slug = $record->slug ?? (string) $record->getKey();
                        return url("/lessons/{$slug}");
                    }
                })
                ->openUrlInNewTab(),
                
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Lição salva!')
            ->body('O conteúdo da lição foi atualizado com sucesso.');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        Log::info('[Fullscreen Editor] mutateFormDataBeforeSave', [
            'id' => $this->record->id,
            'title' => $data['title'] ?? null,
            'content_count' => isset($data['content']) && is_array($data['content']) 
                ? count($data['content']) 
                : 0,
        ]);
        
        return $data;
    }
}
