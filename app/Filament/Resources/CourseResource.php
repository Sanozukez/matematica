<?php

// plataforma/app/Filament/Resources/CourseResource.php

namespace App\Filament\Resources;

use App\Domain\Course\Models\Course;
use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers\ModulesRelationManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

/**
 * Resource do FilamentPHP para gerenciar Cursos
 * 
 * Permite criar, editar e excluir cursos
 * Inclui gerenciamento de módulos via RelationManager
 */
class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Conteúdo';

    protected static ?string $modelLabel = 'Curso';

    protected static ?string $pluralModelLabel = 'Cursos';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações Básicas')
                    ->description('Dados principais do curso')
                    ->schema([
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
                            ->unique(ignoreRecord: true)
                            ->helperText('URL amigável (gerado automaticamente do título)'),

                        Forms\Components\Textarea::make('description')
                            ->label('Descrição')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Configurações')
                    ->schema([
                        Forms\Components\Select::make('level')
                            ->label('Nível')
                            ->options(Course::getLevels())
                            ->required(),

                        Forms\Components\TextInput::make('icon')
                            ->label('Ícone (Emoji)')
                            ->maxLength(50)
                            ->placeholder('📚'),

                        Forms\Components\ColorPicker::make('color')
                            ->label('Cor do Tema'),

                        Forms\Components\TextInput::make('order')
                            ->label('Ordem')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Ativo')
                            ->helperText('Curso visível para alunos'),

                        Forms\Components\Toggle::make('is_gamified')
                            ->label('Gamificado')
                            ->helperText('Usa mini jogos e interações'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('icon')
                    ->label('')
                    ->alignCenter()
                    ->width(50),

                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('level')
                    ->label('Nível')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => 
                        Course::getLevels()[$state] ?? $state
                    )
                    ->color(fn (string $state): string => match ($state) {
                        'basic' => 'success',
                        'fundamental' => 'info',
                        'medium' => 'warning',
                        'advanced' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('modules_count')
                    ->label('Módulos')
                    ->counts('modules')
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_gamified')
                    ->label('Gamificado')
                    ->boolean(),

                Tables\Columns\TextColumn::make('order')
                    ->label('Ordem')
                    ->sortable()
                    ->alignCenter(),
            ])
            ->defaultSort('order')
            ->filters([
                Tables\Filters\SelectFilter::make('level')
                    ->label('Nível')
                    ->options(Course::getLevels()),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Ativo'),

                Tables\Filters\TernaryFilter::make('is_gamified')
                    ->label('Gamificado'),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->reorderable('order');
    }

    public static function getRelations(): array
    {
        return [
            ModulesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
