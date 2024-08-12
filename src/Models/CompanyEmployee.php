<?php

namespace Xbigdaddyx\Companion\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyEmployee extends Pivot
{
    /**
     * The table associated with the pivot model.
     *
     * @var string
     */

    protected $table = 'companion_company_user';

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
