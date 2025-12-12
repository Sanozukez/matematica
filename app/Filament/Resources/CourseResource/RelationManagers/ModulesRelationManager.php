<?php

// plataforma/app/Filament/Resources/CourseResource/RelationManagers/ModulesRelationManager.php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

/**
 * RelationManager para gerenciar Módulos dentro de um Curso
 * 
 * Permite adicionar, editar e remover módulos
 * diretamente na página de edição do curso
 */
class ModulesRelationManager extends RelationManager
{
    protected static string $relationship = 'modules';

    protected static ?string $title = 'Módulos';

    protected static ?string $modelLabel = 'Módulo';

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

                Forms\Components\Textarea::make('description')
                    ->label('Descrição')
                    ->rows(2)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('icon')
                    ->label('Ícone (Emoji)')
                    ->maxLength(50)
                    ->placeholder('📖'),

                Forms\Components\TextInput::make('order')
                    ->label('Ordem')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),

                Forms\Components\Toggle::make('is_active')
                    ->label('Ativo')
                    ->default(false),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('icon')
                    ->label('')
                    ->alignCenter()
                    ->width(50),

                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),

                Tables\Columns\TextColumn::make('lessons_count')
                    ->label('Lições')
                    ->counts('lessons')
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),

                Tables\Columns\TextColumn::make('order')
                    ->label('Ordem')
                    ->sortable()
                    ->alignCenter(),
            ])
            ->defaultSort('order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Ativo'),
            ])
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

