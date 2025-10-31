<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rawilk\FilamentPasswordInput\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';


    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Користувачі';

    protected static ?string $modelLabel = 'Користувачі';
    public static function getPluralLabel(): string
    {
        return 'Користувачі';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Ім\'я')
                    ->required(),
                TextInput::make('email')
                    ->label('Емейл')
                    ->type('email')
                    ->unique(
                        table: User::class,
                        column: 'email',
                        ignoreRecord: true,
                    )
                    ->validationMessages([
                        'unique' => 'Користувач з таким емейлом вже існує.',
                    ])
                    ->required()
                    ->placeholder('Enter Email'),
                Password::make('password')
                    ->label('Пароль')
                    ->required()
                    ->rules('min:8')
                    ->placeholder('Enter Password'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Ім\'я')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Емейл')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
