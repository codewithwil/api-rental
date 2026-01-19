<?php

namespace App\Repositories\Dashboard;

use App\{
    Models\User,
    Models\Resources\Branch\Branch,
    Models\Resources\Vehicle\Vehicle,
    Repositories\Dashboard\DashboardRepositoryInterface
};
use App\Models\Resources\Vehicle\VehicleDepreciat;
use App\Models\Transaction\RentCar\RentCar;
use App\Models\Transactions\Payment\PaymentAmount\PaymentAmount;
use App\Models\Transactions\Vehicle\VehicleRepairRealiz\VehicleRepairRealiz;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function chartIncome(Request $req)
    {
        $year = $req->input('year', Carbon::now()->year); 

        $data = RentCar::query()
            ->join('payment_amounts', function ($join) {
                $join->on('rent_cars.rentCarId', '=', 'payment_amounts.payable_id')
                    ->where('payment_amounts.payable_type', '=', RentCar::class);
            })
            ->selectRaw('MONTH(payment_amounts.date) as month, SUM(payment_amounts.amount) as total')
            ->whereYear('payment_amounts.date', $year)
            ->where('payment_amounts.status', PaymentAmount::STATUS_ACTIVE) 
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $income = [];
        for ($m = 1; $m <= 12; $m++) {
            $income[$m] = $data[$m] ?? 0;
        }

        return [
            'year'   => $year,
            'months' => ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            'income' => array_values($income),
        ];
    }

    public function chartOutcome(Request $req)
    {
        $year  = $req->input('year', Carbon::now()->year);
        $month = $req->input('month'); 

        $expenseQuery = DB::table('vehicle_repair_realizs as vr')
            ->join('payment_amounts as p', function ($join) {
                $join->on('vr.vehcileRepairRealId', '=', 'p.payable_id')
                    ->where('p.payable_type', VehicleRepairRealiz::class)
                    ->where('p.type', PaymentAmount::TYPE_KELUAR);
            })
            ->selectRaw("
                MONTH(p.date) as month,
                SUM(p.amount) as expense
            ")
            ->whereYear('p.date', $year);

        if ($month) {
            $expenseQuery->whereMonth('p.date', $month);
        }

        $expenseQuery->groupBy('month')->orderBy('month');
        $expenses = $expenseQuery->get();

        $expenseData = [];
        for ($m = 1; $m <= 12; $m++) {
            $row = $expenses->firstWhere('month', $m);
            $expense = $row->expense ?? 0;

            $expenseData[] = [
                'month'   => $m,
                'expense' => (float)$expense,
            ];
        }

        return [
            'year'   => $year,
            'month'  => $month,
            'months' => ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            'data'   => $expenseData,
        ];
    }

    public function chartVehicleDepreciate(Request $req)
    {
        $year      = $req->input('year');       
        $vehicleId = $req->input('vehicle_id'); 

        $query = VehicleDepreciat::query()
            ->with('vehicle');

        if ($vehicleId) {
            $query->where('vehicle_id', $vehicleId);
        }

        if ($year) {
            $query->where('year', $year);
        }

        $data = $query->orderBy('year')->get();

        $result = [];
        foreach ($data as $row) {
            $vehicleName = $row->vehicle?->name ?? 'Unknown';

            $result[$vehicleName][] = [
                'year'  => $row->year,
                'depreciation_amount' => $row->depreciation_amount,
                'book_value'          => $row->book_value,
            ];
        }

        return [
            'filters' => [
                'year'      => $year,
                'vehicleId' => $vehicleId,
            ],
            'data' => $result,
        ];
    }

    public function chartProfitLoss(Request $req)
    {
        $year  = $req->input('year', Carbon::now()->year);
        $month = $req->input('month'); 

        $query = DB::table('payment_amounts')
            ->selectRaw("
                MONTH(date) as month,
                SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type = 2 THEN amount ELSE 0 END) as expense
            ")
            ->whereYear('date', $year);

        if ($month) {
            $query->whereMonth('date', $month);
        }

        $query->groupBy('month')->orderBy('month');
        $data = $query->get();

        $debtQuery = DB::table('debts')
            ->selectRaw("
                MONTH(created_at) as month,
                SUM(CASE WHEN status = 0 THEN amount ELSE 0 END) as outstanding,
                SUM(CASE WHEN status = 1 THEN amount ELSE 0 END) as paid
            ")
            ->whereYear('created_at', $year);

        if ($month) {
            $debtQuery->whereMonth('created_at', $month);
        }

        $debtQuery->groupBy('month')->orderBy('month');
        $debts = $debtQuery->get();

        $profitLoss = [];
        for ($m = 1; $m <= 12; $m++) {
            $row  = $data->firstWhere('month', $m);
            $debt = $debts->firstWhere('month', $m);

            $income      = $row->income  ?? 0;
            $expense     = $row->expense ?? 0;
            $outstanding = $debt->outstanding ?? 0;
            $paid        = $debt->paid ?? 0;

            $totalExpense = $expense + $paid;
            $profit       = $income - $totalExpense;

            $profitLoss[] = [
                'month'       => $m,
                'income'      => (float)$income,
                'expense'     => (float)$expense,
                'debt_paid'   => (float)$paid,
                'debt_out'    => (float)$outstanding,
                'profit'      => (float)$profit,
            ];
        }

        return [
            'year'   => $year,
            'month'  => $month,
            'months' => ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            'data'   => $profitLoss,
        ];
    }

    // ====================== Chart Piutang ======================
    public function chartReceivable(Request $req)
    {
        $year = $req->input('year', Carbon::now()->year);

        $query = DB::table('debts')
            ->selectRaw("
                MONTH(created_at) as month,
                SUM(CASE WHEN status = 1 THEN amount ELSE 0 END) as paid
            ")
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month');

        $data = $query->get();
        $paid = [];

        for ($m = 1; $m <= 12; $m++) {
            $row = $data->firstWhere('month', $m);
            $paid[] = $row->paid ?? 0;
        }

        return [
            'year'   => $year,
            'months' => ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            'paid'   => $paid,
        ];
    }

    // ====================== Chart Hutang ======================
    public function chartPayable(Request $req)
    {
        $year  = $req->input('year', Carbon::now()->year);

        $query = DB::table('debts')
            ->selectRaw("
                MONTH(created_at) as month,
                SUM(CASE WHEN status = 0 THEN amount ELSE 0 END) as unpaid
            ")
            ->whereYear('created_at', $year)
            ->where('debtable_type', 'Vendor') 
            ->groupBy('month')
            ->orderBy('month');

        $data   = $query->get();
        $unpaid = [];

        for ($m = 1; $m <= 12; $m++) {
            $row      = $data->firstWhere('month', $m);
            $unpaid[] = $row->unpaid ?? 0;
        }

        return [
            'year'   => $year,
            'months' => ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            'unpaid' => $unpaid,
        ];
    }

}
