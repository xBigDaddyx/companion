<?php

namespace Xbigdaddyx\Companion\Filament\Resources\CompanyResource\Pages;

use Xbigdaddyx\Accuracy\Filament\Accuracy\Resources\PackingListResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Xbigdaddyx\Companion\Filament\Resources\CompanyResource;

class ManageCompanies extends ManageRecords
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
