<?php

namespace Xbigdaddyx\Companion;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Xbigdaddyx\Companion\Filament\Pages\Tenancy\EditCompanyProfile;
use Xbigdaddyx\Companion\Filament\Pages\Tenancy\RegisterCompany;
use Xbigdaddyx\Companion\Filament\Resources\CompanyResource;

class CompanionPlugin implements Plugin
{

    protected bool $hasBuyerResource = true;
    protected bool $hasCartonBoxResource = true;
    protected bool $hasPackingListResource = true;



    public function getId(): string
    {
        return 'companion';
    }

    public function register(Panel $panel): void
    {

        $panel->pages([
            // EditCompanyProfile::class,
            // RegisterCompany::class,

        ])
            ->resources([
                CompanyResource::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
