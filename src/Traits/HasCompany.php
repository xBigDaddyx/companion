<?php

namespace Xbigdaddyx\Companion\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasCompany
{
    public function company(): BelongsTo
    {
        return $this->belongsTo(config('companion.tenant_model'), 'current_company_id', 'id');
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->companies;
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(config('companion.tenant_model'), 'companion_company_user');
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->companies()->whereKey($tenant)->exists();
        //return $this->companies->contains($tenant);
    }
}
