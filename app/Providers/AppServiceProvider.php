<?php

namespace App\Providers;

use App\{
    Repositories\Auth\AuthRepository,
    Repositories\Auth\AuthRepositoryInterface,
    Repositories\People\Admin\AdminRepository,
    Repositories\People\Admin\AdminRepositoryInterface,
    Repositories\People\Employee\EmployeeRepository,
    Repositories\People\Employee\EmployeeRepositoryInterface,
    Repositories\People\Supervisor\SupervisorRepository,
    Repositories\People\Supervisor\SupervisorRepositoryInterface,
    Repositories\Resources\Category\CategoryRepository,
    Repositories\Resources\Category\CategoryRepositoryInterface
};

use Illuminate\{
    Support\ServiceProvider
};

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(SupervisorRepositoryInterface::class, SupervisorRepository::class);
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
    }

    public function boot(): void
    {

    }
}
