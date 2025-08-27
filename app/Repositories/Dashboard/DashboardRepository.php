<?php

namespace App\Repositories\Dashboard;

use App\{
    Models\User,
    Models\Resources\Branch\Branch,
    Models\Resources\Vehicle\Vehicle,
    Repositories\Dashboard\DashboardRepositoryInterface
};

class DashboardRepository implements DashboardRepositoryInterface
{

    public function countUser()
    {
        return User::count();
    }

    public function countBranch()
    {
        return Branch::where('status', Branch::STATUS_ACTIVE)->count();
    }

    public function countVehicle()
    {
        return Vehicle::where('status', '!=', Vehicle::STATUS_DELETED)->count();
    }
}
