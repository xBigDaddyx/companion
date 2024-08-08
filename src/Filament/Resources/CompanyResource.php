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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\HtmlString;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;
use Xbigdaddyx\Companion\Filament\Resources\CompanyResource\Pages\ManageCompanies;
use Xbigdaddyx\Companion\Models\Company;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?int $navigationSort = 9;
    protected static bool $isScopedToTenant = false;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function getNavigationLabel(): string
    {
        return __('companion::companion.resource.label');
    }

    public static function getPluralLabel(): string
    {
        return __('companion::companion.resource.label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('companion::companion.resource.group');
    }

    public function getTitle(): string
    {
        return __('companion::companion.resource.title');
    }

    public static function form(Form $form): Form
    {
        $rows = [

            Section::make('Gallery')
                ->id('gallery-section')
                ->compact()
                ->description(fn () => new HtmlString('<span style="word-break: break-word;">Upload logo here.</span>'))
                ->schema([
                    FileUpload::make('logo')
                        ->image()
                        ->imageEditor()
                        ->removeUploadedFileButtonPosition('center')
                        ->panelAspectRatio('null')
                        ->imagePreviewHeight('64')
                        ->imageEditorMode(2)
                        ->panelLayout('circle')
                        ->downloadable()
                        ->openable(),
                ])->columnSpanFull(),



            Section::make('Company')
                ->id('main-section')
                ->description(fn () => new HtmlString('<span style="word-break: break-word;">Define company information correctly.</span>'))
                ->compact()
                ->schema([

                    TextInput::make('name')
                        ->required()
                        ->label(strval(__('Name'))),
                    TextInput::make('short_name')
                        ->required()
                        ->label(strval(__('Short Name'))),
                    Select::make('owner')
                        ->searchable()
                        ->relationship('owner', 'name')
                        ->label('Owner'),
                ])->columns([
                    'sm' => 1,
                    'lg' => 2,
                ])->columnSpanFull(),
            RichEditor::make('address')
                ->columnSpanFull(),

        ];




        $form->schema($rows);

        return $form;
    }

    public static function table(Table $table): Table
    {

        $table
            ->columns([
                ImageColumn::make('logo')
                    ->circular(),
                TextColumn::make('short_name')
                    ->sortable()
                    ->label(trans('Short Name')),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label(trans('Name')),
                TextColumn::make('address')
                    ->limit(50)
                    ->label(trans('Address')),
                TextColumn::make('created_at')
                    ->label('created_at')
                    ->dateTime('M j, Y')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('updated_at')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => ManageCompanies::route('/'),

        ];
    }
}
