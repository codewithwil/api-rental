<?php

namespace App\Http\Controllers\API\Dashboard;

use App\{
    Http\Controllers\Controller,
    Services\Dashboard\DashboardService,
};
use Illuminate\Http\Request;

class DashboardC extends Controller
{
    public function __construct(protected DashboardService $service) {}

    public function countUser() { return $this->service->countUser(); }
    public function countBranch() { return $this->service->countBranch(); }
    public function countVehicle() { return $this->service->countVehicle(); }
    public function chartIncome(Request $req) { return $this->service->chartIncome($req); }
    public function chartVehicleDepreciate(Request $req) { return $this->service->chartVehicleDepreciate($req); }
}
