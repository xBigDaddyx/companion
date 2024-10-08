<?php

namespace Xbigdaddyx\Companion\Models;

use App\Models\Role as ModelsRole;
use Domain\Accuracies\Models\Buyer;
use Domain\Accuracies\Models\CartonBox;
use Domain\Accuracies\Models\PackingList;
use Domain\Kanban\Models\Plan;
use Domain\Kanban\Models\PlanQueue;
use Domain\Kanban\Models\Rule;
use Domain\Kanban\Models\RunningPlan;
use Domain\Kanban\Models\Sewing;
use Domain\Kanban\Models\Shift;
use Domain\Purchases\Models\ApprovalFlow;
use Domain\Purchases\Models\ApprovalRequest;
use Domain\Purchases\Models\ApprovalUser;
use Domain\Purchases\Models\Category;
use Domain\Purchases\Models\City;
use Domain\Purchases\Models\Currency;
use Domain\Purchases\Models\Department;
use Domain\Purchases\Models\Order;
use Domain\Purchases\Models\Product;
use Domain\Purchases\Models\Request;
use Domain\Purchases\Models\RequestItem;
use Domain\Purchases\Models\Supplier;
use Domain\Purchases\Models\Unit;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Role;
use Xbigdaddyx\Auditor\Models\Area;
use Xbigdaddyx\Auditor\Models\Issue;
use Xbigdaddyx\Auditor\Models\Resolution;
use Xbigdaddyx\Companion\CompanionPlugin;
use Xbigdaddyx\Companion\Traits\HasBaseModels;
use Xbigdaddyx\Companion\Traits\HasProfileLogo;
use Xbigdaddyx\Roomy\Models\Event;
use Xbigdaddyx\Roomy\Models\Room;

class Company extends Model implements HasAvatar, HasCurrentTenantLabel
{
    use HasFactory, HasProfileLogo;
    public function __construct()
    {
        $this->table = 'companion_companies';
    }
    public function getCurrentTenantLabel(): string
    {
        return 'Active company';
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->logo_url;
    }

    protected $guarded = [
        'id',
    ];

    public function getFilamentName(): string
    {
        return "{$this->name} ({$this->short_name})";
    }

    protected $fillable = [
        'name',
        'short_name',
        'logo',
        'owner',
    ];

    protected $casts = [
        'personal_company' => 'boolean',
    ];

    public static function boot()
    {

        parent::boot();
        self::creating(function ($model) {
            $model->personal_company = false;
            $model->created_by = auth()->user()->id;
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(config('companion.user_model'), 'owner', 'id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(config('companion.user_model'), 'created_by', 'id');
    }

    public function users(): BelongsToMany
    {
        //return $this->hasMany(config('companion.user_model'), 'current_company_id', 'id');
        return $this->belongsToMany(config('companion.user_model'), 'companion_company_user')
            ->withPivot('role')
            ->withTimestamps()
            ->using(CompanyEmployee::class);
    }

    // public function members(): BelongsToMany
    // {
    //     return $this->belongsToMany(config('companion.user_model'), 'companion_company_user')
    //         ->withPivot('role')
    //         ->withTimestamps()
    //         > using(CompanyEmployee::class);
    // }

    // public function packingLists(): HasMany
    // {
    //     return $this->hasMany(PackingList::class, 'company_id', 'id');
    // }
    // public function cartonBoxes(): HasMany
    // {
    //     return $this->hasMany(CartonBox::class, 'company_id', 'id');
    // }
    // public function buyers(): HasMany
    // {
    //     return $this->hasMany(Buyer::class, 'company_id', 'id');
    // }
    // public function sewings(): HasMany
    // {
    //     return $this->setConnection('teresa_kanban')->hasMany(Sewing::class, 'company_id', 'id');
    // }
    // public function shifts(): HasMany
    // {
    //     return $this->hasMany(Shift::class, 'company_id', 'id');
    // }
    // public function plans(): HasMany
    // {
    //     return $this->hasMany(Plan::class, 'company_id', 'id');
    // }
    // public function rules(): HasMany
    // {
    //     return $this->hasMany(Rule::class, 'company_id', 'id');
    // }
    // public function rooms(): HasMany
    // {
    //     return $this->hasMany(Room::class, 'company_id', 'id');
    // }
    // public function events(): HasMany
    // {
    //     return $this->hasMany(Event::class, 'company_id', 'id');
    // }
    // public function areas(): HasMany
    // {
    //     return $this->hasMany(Area::class);
    // }
    // public function Issues(): HasMany
    // {
    //     return $this->hasMany(Issue::class, 'company_id', 'id');
    // }
    // public function Resolutions(): HasMany
    // {
    //     return $this->hasMany(Resolution::class, 'company_id', 'id');
    // }
    // public function runningPlans(): HasMany
    // {
    //     return $this->hasMany(RunningPlan::class, 'company_id', 'id');
    // }
    // public function plan_queues(): HasMany
    // {
    //     return $this->hasMany(PlanQueue::class, 'company_id', 'id');
    // }
    // public function requests(): HasMany
    // {
    //     return $this->hasMany(Request::class, 'company_id', 'id');
    // }
    // public function orders(): HasMany
    // {
    //     return $this->hasMany(Order::class, 'company_id', 'id');
    // }
    // public function currencies(): HasMany
    // {
    //     return $this->hasMany(Currency::class, 'company_id', 'id');
    // }
    // public function categories(): HasMany
    // {
    //     return $this->hasMany(Category::class, 'company_id', 'id');
    // }
    // public function cities(): HasMany
    // {
    //     return $this->hasMany(City::class, 'company_id', 'id');
    // }
    // public function suppliers(): HasMany
    // {
    //     return $this->hasMany(Supplier::class, 'company_id', 'id');
    // }
    // public function units(): HasMany
    // {
    //     return $this->hasMany(Unit::class, 'company_id', 'id');
    // }
    // public function productCategories(): HasMany
    // {
    //     return $this->hasMany(Unit::class, 'company_id', 'id');
    // }
    // public function products(): HasMany
    // {
    //     return $this->hasMany(Product::class, 'company_id', 'id');
    // }
    // public function departments(): HasMany
    // {
    //     return $this->hasMany(Department::class, 'company_id', 'id');
    // }
    // public function approvalRequests(): HasMany
    // {
    //     return $this->hasMany(ApprovalRequest::class, 'company_id', 'id');
    // }
    // public function approvalFlows(): HasMany
    // {
    //     return $this->hasMany(ApprovalFlow::class, 'company_id', 'id');
    // }
    // public function approvalUsers()
    // {
    //     return $this->hasMany(Approvalconfig('companion.user_model'), 'company_id', 'id');
    // }
    // public function planQueues()
    // {
    //     return $this->hasMany(PlanQueue::class, 'company_id', 'id');
    // }
    // public function requestItems()
    // {
    //     return $this->hasMany(RequestItem::class, 'company_id', 'id');
    // }
    // public function purchaseDepartments()
    // {
    //     return $this->hasMany(PurchaseDepartment::class);
    // }
    protected $appends = [
        'logo_url',
    ];
}
