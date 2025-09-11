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
use Carbon\Carbon;
use Illuminate\Http\Request;

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
            ->selectRaw('MONTH(payment_amounts.created_at) as month, SUM(payment_amounts.amount) as total')
            ->whereYear('payment_amounts.created_at', $year)
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


}
