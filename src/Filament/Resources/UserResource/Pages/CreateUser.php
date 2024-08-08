<?php

namespace Xbigdaddyx\Companion\Filament\Resources\UserResource\Pages;


use Filament\Resources\Pages\CreateRecord;
use Xbigdaddyx\Companion\Filament\Resources\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return trans('companion::companion.resource.user.title.create');
    }
}
