<?php

namespace App\Repositories\Dashboard;

use Illuminate\Http\Request;

interface DashboardRepositoryInterface
{
    public function countUser();
    public function countBranch();
    public function countVehicle();
    public function chartIncome(Request $req);
    public function chartOutcome(Request $req);
    public function chartVehicleDepreciate(Request $req);
    public function chartProfitLoss(Request $req);
    public function chartReceivable(Request $req);
    public function chartPayable(Request $req);
}
