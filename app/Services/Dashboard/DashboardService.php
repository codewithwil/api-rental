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

    public function chartOutcome(Request $req)
    {
        return $this->successResponse([
            'outcome' => $this->dashboardRepo->chartOutcome($req)
        ]);
    }

    public function chartVehicleDepreciate(Request $req)
    {
        return $this->successResponse([
            'vehicle_depreciate' => $this->dashboardRepo->chartVehicleDepreciate($req)
        ]);
    }

    public function chartProfitLoss(Request $req){
        return $this->successResponse([
            'outincome' => $this->dashboardRepo->chartProfitLoss($req)
        ]);
    }
    
    public function chartReceivable(Request $req){
        return $this->successResponse([
            'receivable' => $this->dashboardRepo->chartReceivable($req)
        ]);
    }

    public function chartPayable(Request $req){
        return $this->successResponse([
            'payable' => $this->dashboardRepo->chartPayable($req)
        ]);
    }
}
