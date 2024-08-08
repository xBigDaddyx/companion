<?php

namespace Xbigdaddyx\Companion\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Filament\Tables\Columns\ImageColumn;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;
use Xbigdaddyx\Companion\Filament\Resources\UserResource\Pages\CreateUser;
use Xbigdaddyx\Companion\Filament\Resources\UserResource\Pages\EditUser;
use Xbigdaddyx\Companion\Filament\Resources\UserResource\Pages\ListUsers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 9;
    // protected static bool $isScopedToTenant = false;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationLabel(): string
    {
        return trans('companion::companion.resource.user.label');
    }

    public static function getPluralLabel(): string
    {
        return trans('companion::companion.resource.user.label');
    }

    public static function getLabel(): string
    {
        return trans('companion::companion.resource.user.single');
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('companion::companion.resource.user.group');
    }

    public function getTitle(): string
    {
        return trans('companion::companion.resource.user.title.resource');
    }

    public static function form(Form $form): Form
    {
        $rows = [
            TextInput::make('name')
                ->required()
                ->label(trans('companion::companion.resource.user.name')),
            TextInput::make('email')
                ->email()
                ->required()
                ->label(trans('companion::companion.resource.user.email')),
            TextInput::make('password')
                ->label(trans('companion::companion.resource.user.password'))
                ->password()
                ->maxLength(255)
                ->dehydrateStateUsing(static function ($state) use ($form) {
                    return !empty($state)
                        ? Hash::make($state)
                        : User::find($form->getColumns())?->password;
                }),
        ];


        if (config('filament-users.shield') && class_exists(\BezhanSalleh\FilamentShield\FilamentShield::class)) {
            $rows[] = Forms\Components\Select::make('roles')
                ->multiple()
                ->preload()
                ->relationship('roles', 'name')
                ->label(trans('companion::companion.resource.user.roles'));
        }

        $form->schema($rows);

        return $form;
    }

    public static function table(Table $table): Table
    {
        if (class_exists(\STS\FilamentImpersonate\Tables\Actions\Impersonate::class) && config('filament-users.impersonate')) {
            $table->actions([Impersonate::make('impersonate')]);
        }
        $table
            ->columns([
                ImageColumn::make('avatar')
                    ->circular(),
                TextColumn::make('id')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->label(trans('companion::companion.resource.user.id')),
                TextColumn::make('employee_id'),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label(trans('companion::companion.resource.user.name')),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->label(trans('companion::companion.resource.user.email')),
                TextColumn::make('department'),
                IconColumn::make('email_verified_at')
                    ->boolean()
                    ->sortable()
                    ->searchable()
                    ->label(trans('companion::companion.resource.user.email_verified_at')),
                TextColumn::make('companies.short_name')
                    ->color('info')
                    ->badge(),
                TextColumn::make('roles.name')
                    ->badge(),
                TextColumn::make('phone')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('created_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(trans('companion::companion.resource.user.created_at'))
                    ->dateTime('M j, Y')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(trans('companion::companion.resource.user.updated_at'))
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('verified')
                    ->label(trans('companion::companion.resource.user.verified'))
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at')),
                Tables\Filters\Filter::make('unverified')
                    ->label(trans('companion::companion.resource.user.unverified'))
                    ->query(fn (Builder $query): Builder => $query->whereNull('email_verified_at')),
            ])
            ->actions([
                Impersonate::make(),
                ActionGroup::make([

                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()
                ]),
            ]);
        return $table;
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
