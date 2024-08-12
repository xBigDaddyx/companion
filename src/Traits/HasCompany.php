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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Xbigdaddyx\Companion\Models\Company;
use Xbigdaddyx\Companion\Models\CompanyEmployee;

trait HasCompany
{
    // public function company(): BelongsTo
    // {
    //     return $this->belongsTo(config('companion.tenant_model'), 'current_company_id', 'id');
    // }

    public function getTenants(Panel $panel): Collection
    {
        return $this->companies;
    }
    /**
     * Get the company that owns the HasCompany
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'current_company_id', 'id');
    }
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'companion_company_user')
            ->withPivot('role')
            ->withTimestamps()
            ->using(CompanyEmployee::class);
    }
    public function userCompanies(): HasMany
    {
        return $this->hasMany(CompanyEmployee::class);
    }


    public function canAccessTenant(Model $tenant): bool
    {
        return $this->companies()->whereKey($tenant)->exists();
    }
}
