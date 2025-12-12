<?php

// plataforma/app/Filament/Resources/LessonResource.php

namespace App\Filament\Resources;

use App\Domain\Lesson\Models\Lesson;
use App\Filament\Resources\LessonResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

/**
 * Resource do FilamentPHP para gerenciar Lições
 * 
 * Sistema de Blocos Refatorado:
 * - Blocos modulares em classes separadas (SRP)
 * - BlockRegistry para gerenciamento centralizado
 * - Editor fullscreen para lições de texto
 * - Fácil manutenção e extensão
 */
class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Conteúdo';

    protected static ?string $modelLabel = 'Lição';

    protected static ?string $pluralModelLabel = 'Lições';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Informações Básicas
                Forms\Components\Section::make('Informações Básicas')
                    ->schema([
                        Forms\Components\Select::make('module_id')
                            ->label('Módulo')
                            ->relationship(
                                name: 'module',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn ($query) => $query->orderBy('title')
                            )
                            ->required()
                            ->searchable()
                            ->preload()
                            ->helperText('Selecione o módulo ao qual esta lição pertence'),

                        Forms\Components\TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, $set) => 
                                $set('slug', Str::slug($state))
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(
                                table: 'lessons',
                                column: 'slug',
                                ignoreRecord: true,
                                modifyRuleUsing: function ($rule, $get) {
                                    return $rule->where('module_id', $get('module_id'));
                                }
                            )
                            ->helperText('URL amigável (gerado automaticamente do título)'),

                        Forms\Components\Select::make('type')
                            ->label('Tipo de Conteúdo')
                            ->options(Lesson::getTypes())
                            ->required()
                            ->default('text')
                            ->live()
                            ->afterStateUpdated(fn ($set) => $set('content', null)),
                    ])
                    ->columns(2),

                // Configurações
                Forms\Components\Section::make('Configurações')
                    ->schema([
                        Forms\Components\TextInput::make('duration_minutes')
                            ->label('Duração Estimada (minutos)')
                            ->numeric()
                            ->default(5)
                            ->minValue(1)
                            ->suffix('min'),

                        Forms\Components\TextInput::make('order')
                            ->label('Ordem')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Ativa')
                            ->helperText('Lição visível para alunos'),
                    ])
                    ->columns(3),

                // Conteúdo - Tipo Text
                Forms\Components\Section::make('Conteúdo')
                    ->description('O conteúdo da lição será editado no editor fullscreen após salvar. Clique em "Editor" na lista de lições para editar o conteúdo.')
                    ->schema([
                        Forms\Components\Placeholder::make('content_note')
                            ->label('')
                            ->content('💡 Após criar/editar esta lição, use o botão "Editor" na lista de lições para editar o conteúdo completo.')
                    ])
                    ->visible(fn ($get) => $get('type') === 'text')
                    ->collapsible()
                    ->collapsed(),

                // Conteúdo - Vídeo
                Forms\Components\Section::make('Vídeo')
                    ->description('Cole a URL do vídeo do YouTube ou Bunny.net')
                    ->schema([
                        Forms\Components\Select::make('video_data.provider')
                            ->label('Provedor')
                            ->options([
                                'youtube' => '📺 YouTube',
                                'bunny' => '🐰 Bunny Stream',
                                'vimeo' => '🎬 Vimeo',
                                'direct' => '🔗 URL Direta',
                            ])
                            ->default('youtube')
                            ->required(),

                        Forms\Components\TextInput::make('video_data.url')
                            ->label('URL do Vídeo')
                            ->url()
                            ->placeholder('https://youtube.com/watch?v=...')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('video_data.description')
                            ->label('Notas/Descrição')
                            ->rows(3)
                            ->placeholder('Pontos importantes do vídeo, timestamps, etc.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn ($get) => $get('type') === 'video'),

                // Conteúdo - Quiz
                Forms\Components\Section::make('Perguntas do Quiz')
                    ->description('Adicione perguntas de múltipla escolha')
                    ->schema([
                        Forms\Components\Repeater::make('quiz_data.questions')
                            ->label('')
                            ->schema([
                                Forms\Components\Textarea::make('question')
                                    ->label('Pergunta')
                                    ->required()
                                    ->rows(2),

                                Forms\Components\Repeater::make('options')
                                    ->label('Opções de Resposta')
                                    ->schema([
                                        Forms\Components\TextInput::make('text')
                                            ->label('Opção')
                                            ->required(),

                                        Forms\Components\Toggle::make('is_correct')
                                            ->label('Correta?')
                                            ->inline(false),
                                    ])
                                    ->columns(2)
                                    ->minItems(2)
                                    ->maxItems(5)
                                    ->defaultItems(4)
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => 
                                        $state['text'] ?? 'Nova opção'
                                    ),

                                Forms\Components\Textarea::make('explanation')
                                    ->label('Explicação (exibida após responder)')
                                    ->rows(2)
                                    ->helperText('Explique por que a resposta está correta'),
                            ])
                            ->columns(1)
                            ->collapsible()
                            ->cloneable()
                            ->itemLabel(fn (array $state): ?string => 
                                Str::limit($state['question'] ?? 'Nova pergunta', 50)
                            )
                            ->addActionLabel('Adicionar Pergunta')
                            ->defaultItems(1),
                    ])
                    ->visible(fn ($get) => $get('type') === 'quiz'),

                // Conteúdo - Game
                Forms\Components\Section::make('Configuração do Mini Jogo')
                    ->description('Configure o mini jogo interativo')
                    ->schema([
                        Forms\Components\Select::make('game_data.type')
                            ->label('Tipo de Jogo')
                            ->options([
                                'counting' => '🔢 Contagem',
                                'matching' => '🎯 Associação',
                                'ordering' => '📊 Ordenação',
                                'puzzle' => '🧩 Quebra-cabeça',
                                'memory' => '🧠 Memória',
                                'drag_drop' => '✋ Arrastar e Soltar',
                            ])
                            ->required(),

                        Forms\Components\Select::make('game_data.difficulty')
                            ->label('Dificuldade')
                            ->options([
                                'easy' => '🟢 Fácil',
                                'medium' => '🟡 Médio',
                                'hard' => '🔴 Difícil',
                            ])
                            ->default('easy'),

                        Forms\Components\TextInput::make('game_data.time_limit')
                            ->label('Tempo Limite (segundos)')
                            ->numeric()
                            ->placeholder('Sem limite')
                            ->helperText('Deixe vazio para sem limite'),

                        Forms\Components\TextInput::make('game_data.points')
                            ->label('Pontos ao Completar')
                            ->numeric()
                            ->default(10),

                        Forms\Components\KeyValue::make('game_data.config')
                            ->label('Configurações Específicas')
                            ->keyLabel('Parâmetro')
                            ->valueLabel('Valor')
                            ->addActionLabel('Adicionar Parâmetro')
                            ->helperText('Configurações específicas do tipo de jogo')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('game_data.instructions')
                            ->label('Instruções para o Aluno')
                            ->rows(3)
                            ->placeholder('Explique como jogar este jogo...')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn ($get) => $get('type') === 'game'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('module.course.title')
                    ->label('Curso')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('module.title')
                    ->label('Módulo')
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => 
                        Lesson::getTypes()[$state] ?? $state
                    )
                    ->color(fn (string $state): string => match ($state) {
                        'text' => 'gray',
                        'video' => 'info',
                        'quiz' => 'warning',
                        'game' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('duration_minutes')
                    ->label('Duração')
                    ->suffix(' min')
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Ativa')
                    ->boolean(),

                Tables\Columns\TextColumn::make('order')
                    ->label('Ordem')
                    ->sortable()
                    ->alignCenter(),
            ])
            ->defaultSort('module_id')
            ->filters([
                Tables\Filters\SelectFilter::make('module_id')
                    ->label('Módulo')
                    ->relationship('module', 'title')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options(Lesson::getTypes()),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Ativa'),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // Edição básica (metadados) com engrenagem
                Tables\Actions\Action::make('edit')
                    ->label('Editar')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url(fn (Lesson $record): string => LessonResource::getUrl('edit', ['record' => $record]))
                    ->visible(fn (Lesson $record): bool => !$record->trashed()),

                // Editor de Blocos (TipTap/Builder)
                Tables\Actions\Action::make('editor')
                    ->label('Editor')
                    ->icon('heroicon-o-squares-plus')
                    ->color('primary')
                    ->visible(fn (Lesson $record): bool => $record->type === 'text' && !$record->trashed())
                    ->url(fn (Lesson $record): string => LessonResource::getUrl('editor', ['record' => $record])),

                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
            'editor' => Pages\EditLessonFullscreen::route('/{record}/editor'),
        ];
    }
}
