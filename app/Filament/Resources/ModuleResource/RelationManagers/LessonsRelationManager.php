<?php

// plataforma/app/Filament/Resources/ModuleResource/RelationManagers/LessonsRelationManager.php

namespace App\Filament\Resources\ModuleResource\RelationManagers;

use App\Domain\Lesson\Models\Lesson;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

/**
 * RelationManager para gerenciar Lições dentro de um Módulo
 */
class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons';

    protected static ?string $title = 'Lições';

    protected static ?string $modelLabel = 'Lição';

    public function form(Form $form): Form
    {
        return $form
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
                    ->maxLength(255),

                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->options(Lesson::getTypes())
                    ->required()
                    ->default('text'),

                Forms\Components\TextInput::make('duration_minutes')
                    ->label('Duração (min)')
                    ->numeric()
                    ->default(5)
                    ->minValue(1),

                Forms\Components\TextInput::make('order')
                    ->label('Ordem')
                    ->numeric()
                    ->default(0),

                Forms\Components\Toggle::make('is_active')
                    ->label('Ativa')
                    ->default(false),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),

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
            ->defaultSort('order')
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('order');
    }
}

