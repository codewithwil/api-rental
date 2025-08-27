<?php

namespace App\Services\Dashboard;

use App\{
    Repositories\Dashboard\DashboardRepositoryInterface,
    Traits\ApiResponse,
};

class DashboardService
{
    use ApiResponse;

    public function __construct(protected DashboardRepositoryInterface $dashboardRepo) {}

    public function countUser()
    {
        return $this->successResponse([
            'users' => $this->dashboardRepo->countUser()
        ]);
    }

    public function countBranch()
    {
        return $this->successResponse([
            'branch' => $this->dashboardRepo->countBranch()
        ]);
    }

    public function countVehicle()
    {
        return $this->successResponse([
            'vehicle' => $this->dashboardRepo->countVehicle()
        ]);
    }
}
