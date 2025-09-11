<?php

namespace App\Services\Dashboard;

use App\{
    Repositories\Dashboard\DashboardRepositoryInterface,
    Traits\ApiResponse,
};
use Illuminate\Http\Request;

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

    public function chartIncome(Request $req)
    {
        return $this->successResponse([
            'income' => $this->dashboardRepo->chartIncome($req)
        ]);
    }

    public function chartVehicleDepreciate(Request $req)
    {
        return $this->successResponse([
            'vehicle_depreciate' => $this->dashboardRepo->chartVehicleDepreciate($req)
        ]);
    }
}
