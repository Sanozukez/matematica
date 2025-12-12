<?php

// plataforma/app/Filament/Resources/BadgeResource.php

namespace App\Filament\Resources;

use App\Domain\Badge\Models\Badge;
use App\Filament\Resources\BadgeResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

/**
 * Resource do FilamentPHP para gerenciar Badges
 */
class BadgeResource extends Resource
{
    protected static ?string $model = Badge::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationGroup = 'Gamificação';

    protected static ?string $modelLabel = 'Badge';

    protected static ?string $pluralModelLabel = 'Badges';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações da Badge')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
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
                            ->unique(ignoreRecord: true),

                        Forms\Components\Textarea::make('description')
                            ->label('Descrição')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Aparência')
                    ->schema([
                        Forms\Components\TextInput::make('icon')
                            ->label('Ícone (Emoji)')
                            ->maxLength(50)
                            ->placeholder('🏆'),

                        Forms\Components\ColorPicker::make('color')
                            ->label('Cor'),

                        Forms\Components\TextInput::make('points')
                            ->label('Pontos')
                            ->numeric()
                            ->default(10)
                            ->minValue(0),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Associações')
                    ->schema([
                        Forms\Components\Select::make('module_id')
                            ->label('Módulo Associado')
                            ->relationship('module', 'title')
                            ->searchable()
                            ->preload()
                            ->helperText('Badge será desbloqueada ao completar este módulo'),

                        Forms\Components\Select::make('prerequisites')
                            ->label('Pré-requisitos')
                            ->relationship('prerequisites', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->helperText('Badges que precisam ser desbloqueadas antes'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Ativa')
                            ->default(true),
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

                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('module.title')
                    ->label('Módulo')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('points')
                    ->label('Pontos')
                    ->alignCenter()
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('prerequisites_count')
                    ->label('Pré-req.')
                    ->counts('prerequisites')
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Ativa')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Ativa'),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBadges::route('/'),
            'create' => Pages\CreateBadge::route('/create'),
            'edit' => Pages\EditBadge::route('/{record}/edit'),
        ];
    }
}

