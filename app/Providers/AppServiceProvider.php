<?php

namespace App\Providers;

use App\{
    Repositories\Auth\AuthRepository,
    Repositories\Auth\AuthRepositoryInterface,
    Repositories\People\AllPeople\AllPeopleRepository,
    Repositories\People\AllPeople\AllPeopleRepositoryInterface,
    Repositories\People\Admin\AdminRepository,
    Repositories\People\Admin\AdminRepositoryInterface,
    Repositories\People\Employee\EmployeeRepository,
    Repositories\People\Employee\EmployeeRepositoryInterface,
    Repositories\People\Supervisor\SupervisorRepository,
    Repositories\People\Supervisor\SupervisorRepositoryInterface,
    Repositories\Resources\Category\CategoryRepository,
    Repositories\Resources\Category\CategoryRepositoryInterface,
    Repositories\Resources\Brand\BrandRepository,
    Repositories\Resources\Brand\BrandRepositoryInterface,
    Repositories\Resources\Company\CompanyRepository,
    Repositories\Resources\Company\CompanyRepositoryInterface,
    Repositories\Resources\Rules\RulesRepository,
    Repositories\Resources\Rules\RulesRepositoryInterface,
    Repositories\Resources\Branch\BranchRepository,
    Repositories\Resources\Branch\BranchRepositoryInterface,
    Repositories\Resources\Vehicle\VehicleRepository,
    Repositories\Resources\Vehicle\VehicleRepositoryInterface,
    Repositories\Resources\VehicleDepreciate\VehicleDepreciateRepository,
    Repositories\Resources\VehicleDepreciate\VehicleDepreciateRepositoryInterface
};

use Illuminate\{
    Support\ServiceProvider
};

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(AllPeopleRepositoryInterface::class, AllPeopleRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(SupervisorRepositoryInterface::class, SupervisorRepository::class);
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
        $this->app->bind(RulesRepositoryInterface::class, RulesRepository::class);
        $this->app->bind(BranchRepositoryInterface::class, BranchRepository::class);
        $this->app->bind(VehicleRepositoryInterface::class, VehicleRepository::class);
        $this->app->bind(VehicleDepreciateRepositoryInterface::class, VehicleDepreciateRepository::class);
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
