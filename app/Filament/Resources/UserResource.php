<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort     = 200;

    protected static ?string $modelLabel = 'Пользователь';
    protected static ?string $pluralModelLabel = 'Пользователи';
    protected static ?string $navigationLabel = 'Пользователи';

    protected static string | \UnitEnum | null $navigationGroup = 'Аутентификация';

    protected static ?string $name      = 'ФИО';
    protected static ?string $email     = 'Адрес ел. почты';
    protected static ?string $email_verified_at= 'Почта подтверждена';
    protected static ?string $password  = 'Пароль';
    protected static ?string $roles     = 'Роли';
    
//    public static function canViewAny(): bool
//    {
//        return auth()->user()->can('управлять пользователями');
//    }    

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label(__(static::$name)),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->label(__(static::$email)),
                TextInput::make('password')
                    ->label(static::$password)
                    ->password()
                    ->maxLength(255)
                    ->dehydrateStateUsing(static function ($state, $record) use ($schema) {
                            return !empty($state)
                                ? Hash::make($state)
                                : $record->password;
                        }),

                Select::make('roles')
                    ->relationship(name: 'roles', titleAttribute: 'name')
//                    ->saveRelationshipsUsing(function (Model $record, $state) {
//                            $record->roles()->syncWithPivotValues($state, [config('permission.column_names.team_foreign_key') => getPermissionsTeamId()]);
//                        })
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__(static::$name))                
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__(static::$email)),
                IconColumn::make('email_verified_at')
                    ->boolean()
                    ->sortable()
                    ->searchable()
                    ->label(__(static::$email_verified_at)),
                TextColumn::make('roles.name')
                    ->label(__(static::$roles))  
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->iconButton()->tooltip(trans('filament-users::user.resource.title.show')),
                EditAction::make()->iconButton()->tooltip(trans('filament-users::user.resource.title.edit')),
                DeleteAction::make()->iconButton()->tooltip(trans('filament-users::user.resource.title.delete'))
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
