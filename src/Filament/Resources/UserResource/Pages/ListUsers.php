<?php

namespace Xbigdaddyx\Companion\Filament\Resources\UserResource\Pages;


use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
use Xbigdaddyx\Companion\Filament\Resources\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return trans('companion::companion.resource.user.title.list');
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
