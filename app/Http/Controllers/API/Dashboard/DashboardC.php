<?php

namespace App\Http\Controllers\API\Dashboard;

use App\{
    Http\Controllers\Controller,
    Services\Dashboard\DashboardService,
};

class DashboardC extends Controller
{
    public function __construct(protected DashboardService $service) {}

    public function countUser() { return $this->service->countUser(); }
    public function countBranch() { return $this->service->countBranch(); }
    public function countVehicle() { return $this->service->countVehicle(); }
}
