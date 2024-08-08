<?php

namespace Xbigdaddyx\Companion;

use App\Application;
use Illuminate\Support\ServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Illuminate\Support\Facades\Gate;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Livewire;
use Xbigdaddyx\Companion\Console\Commands\ChangeIssueStatus;
use Xbigdaddyx\Companion\Console\Commands\CheckIssueStatus;
use Xbigdaddyx\Companion\Events\CompanionCorrectionCreatedEvent;
use Xbigdaddyx\Companion\Events\CompanionIssueCreatedEvent;
use Xbigdaddyx\Companion\Events\CorrectionApprovedEvent;
use Xbigdaddyx\Companion\Events\CorrectionCreatedEvent;
use Xbigdaddyx\Companion\Events\CorrectionRejectedEvent;
use Xbigdaddyx\Companion\Events\IssueCreatedEvent;
use Xbigdaddyx\Companion\Events\IssuePendingEvent;
use Xbigdaddyx\Companion\Events\IssueResolvedEvent;
use Xbigdaddyx\Companion\Listeners\SendCompanionCorrectionCreatedNotification;
use Xbigdaddyx\Companion\Listeners\SendCompanionIssueCreatedNotification;
use Xbigdaddyx\Companion\Listeners\SendCorrectionApprovedNotification;
use Xbigdaddyx\Companion\Listeners\SendCorrectionCreatedNotification;
use Xbigdaddyx\Companion\Listeners\SendCorrectionRejectedNotification;
use Xbigdaddyx\Companion\Listeners\SendIssueCreatedNotification;
use Xbigdaddyx\Companion\Listeners\SendIssuePendingNotification;
use Xbigdaddyx\Companion\Listeners\SendIssueResolvedNotification;
use Xbigdaddyx\Companion\Models\Area;
use Xbigdaddyx\Companion\Models\Issue;
use Xbigdaddyx\Companion\Models\Resolution;
use Xbigdaddyx\Companion\Policies\AreaPolicy;
use Xbigdaddyx\Companion\Policies\IssuePolicy;
use Xbigdaddyx\Companion\Policies\ResolutionPolicy;

class CompanionServiceProvider extends PackageServiceProvider
{
    public static string $name = 'companion';

    public static string $viewNamespace = 'companion';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)

            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('xbigdaddyx/Companion');
            })
            ->hasViews(static::$viewNamespace);

        $configFileName = $package->shortName();
        if (file_exists($package->basePath("/../routes/web.php"))) {
            $package->hasRoutes("web");
        }
        if (file_exists($package->basePath("/../routes/api.php"))) {
            $package->hasRoutes("api");
        }

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }
    protected function getCommands(): array
    {
        return [
            // ChangeIssueStatus::class,
            // CheckIssueStatus::class,
        ];
    }
    protected function getMigrations(): array
    {
        return [
            'add_current_company_id_column_to_user_table_02082024_10_46',
            'create_companies_table_02082024_10_40'
            // '20230109_1032_create_Companion_types_table',
            // '20230109_1033_create_Companion_documents_table'
        ];
    }
    public function packageRegistered(): void
    {
        //$this->app->bind('VerificationRepository', \Xbigdaddyx\Companion\Repositories\VerificationRepository::class);
        //$this->app->bind('SearchRepository', \Xbigdaddyx\Companion\Repositories\SearchRepository::class);
        // $this->app->register(CompanionEventServiceProvider::class);
        //$this->app->reqister(EventServiceProvider::class);
        //$this->register(EventServiceProvider::class);
        // $this->app->register(EventServiceProvider::class);
    }

    public function packageBooted(): void
    {

        //Event::listen(CartonBoxStatusUpdated::class, CartonBoxStatusListener::class);
        // Event::listen(IssueCreatedEvent::class, SendIssueCreatedNotification::class);
        // Event::listen(IssuePendingEvent::class, SendIssuePendingNotification::class);
        // Event::listen(IssueResolvedEvent::class, SendIssueResolvedNotification::class);
        // Event::listen(CompanionIssueCreatedEvent::class, SendCompanionIssueCreatedNotification::class);
        // Event::listen(CompanionCorrectionCreatedEvent::class, SendCompanionCorrectionCreatedNotification::class);
        // Event::listen(CorrectionRejectedEvent::class, SendCorrectionRejectedNotification::class);
        // Event::listen(CorrectionApprovedEvent::class, SendCorrectionApprovedNotification::class);
        // Event::listen(CorrectionCreatedEvent::class, SendCorrectionCreatedNotification::class);
        // $this->callAfterResolving(BladeCompiler::class, function () {

        //     if (class_exists(Livewire::class)) {
        //         // Livewire::component('search-carton', SearchCarton::class);
        //         // Livewire::component('verification-carton', VerificationCarton::class);
        //         // Livewire::component('companion-polybag-attributes', CompanionPolybagAttributes::class);
        //         // Livewire::component('companion-polybag-stats', CompanionPolybagStats::class);
        //         // Livewire::component('companion-polybag-table', CompanionPolybagTable::class);
        //         // Livewire::component('status', Status::class);
        //         // Livewire::component('paginator', RevisionsPaginator::class);
        //         // Livewire::component('version', Version::class);
        //     }
        // });
        // Gate::policy(Issue::class, IssuePolicy::class);
        // Gate::policy(Area::class, AreaPolicy::class);
        // Gate::policy(Resolution::class, ResolutionPolicy::class);
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName(),

        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        // if (app()->runningInConsole()) {
        //     foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
        //         $this->publishes([
        //             $file->getRealPath() => base_path("stubs/Companion/{$file->getFilename()}"),
        //         ], 'Companion-stubs');
        //     }
        // }

        // Testing
        // Testable::mixin(new TestsApproval());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'xbigdaddyx/Companion';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // Css::make('Companion-paginator-styles', __DIR__ . '/../resources/dist/paginator.css'),
            // // AlpineComponent::make('filament-approvals', __DIR__ . '/../resources/dist/components/filament-approvals.js'),
            // Css::make('Companion-styles', __DIR__ . '/../resources/dist/Companion.css'),
            // Js::make('Companion-scripts', __DIR__ . '/../resources/dist/Companion.js'),
        ];
    }

    protected function getIcons(): array
    {
        return [];
    }


    protected function getRoutes(): array
    {
        return [];
    }


    protected function getScriptData(): array
    {
        return [];
    }
}
