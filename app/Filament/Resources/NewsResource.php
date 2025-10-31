<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Models\News;
use App\Models\Tag;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use Rawilk\FilamentPasswordInput\Password;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Новини';

    protected static ?string $modelLabel = 'Новина';
    public static function getPluralLabel(): string
    {
        return 'Новини';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Заголовок')
                    ->required(),
                SpatieMediaLibraryFileUpload::make('image')
                    ->label('Зображення')
                    ->columnSpanFull()
                    ->collection('image')
                    ->disk('public'),
               TinyEditor::make('content')
                    ->label('Контент')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\Toggle::make('is_status')
                    ->label('Активний')
                    ->onColor('success')
                    ->offColor('danger')->default(true),

                Repeater::make('tags')
                    ->label('Теги')
                ->relationship('tags')
                    ->schema([
                        TextInput::make('name')
                            ->label('Тег')
                            ->required()
                            ->unique(table: Tag::class, column: 'name',  ignoreRecord: true,)
                            ->validationMessages([
                                'unique' => 'Данний тег вже існує в іншій новині.',
                            ])
                            ->maxLength(50),
                    ])
                    ->columnSpanFull()
                    ->createItemButtonLabel('Додати тег'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Дата створення')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                SpatieMediaLibraryImageColumn::make('image')
                    ->label('Фото')
                    ->collection('image')
                    ->conversion('webp'),
                TextColumn::make('title')
                     ->label('Заголовок')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_status')
                    ->label('Статус'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                DeleteAction::make()
                    ->modalHeading(fn ($record) => "Видалити новину: «{$record->title}»")
                    ->modalSubheading('Цю дію неможливо буде відмінити.')
                    ->modalButton('Видалити')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
