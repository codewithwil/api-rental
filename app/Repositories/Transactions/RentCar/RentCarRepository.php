<?php

namespace App\Repositories\Transactions\RentCar;

use App\{
    Repositories\Transactions\RentCar\RentCarRepositoryInterface,
    Models\Transaction\RentCar\RentCar,
    Traits\DbTransaction,
    Models\Resources\Vehicle\Vehicle,
    Models\Transactions\Payment\PaymentAmount\PaymentAmount,
    Models\Transactions\ReturnRentCar\ReturnRentCar,
    Models\Transactions\Debt\Debt
};

use Illuminate\{
    Http\Request
};

use Carbon\{
    Carbon
};
use Illuminate\Support\Facades\DB;

class RentCarRepository implements RentCarRepositoryInterface
{
    use DbTransaction;

    public function getAll(Request $req)
    {
        return RentCar::with(['vehicle', 'paymentAmount'])->paginate(10);
    }

public function getSelected()
{
    $sub = DB::table('rent_cars as rc')
        ->select(
            'rc.rentCarId',
            'rc.vehicle_id',
            DB::raw('ROW_NUMBER() OVER (PARTITION BY rc.vehicle_id ORDER BY rc.created_at DESC, rc.rentCarId DESC) as row_num')
        );

    $latest = DB::table(DB::raw("({$sub->toSql()}) as ranked"))
        ->mergeBindings($sub)
        ->where('ranked.row_num', 1)
        ->pluck('ranked.rentCarId');

    return RentCar::with(['vehicle', 'paymentAmount'])
        ->whereIn('rentCarId', $latest)
        ->whereHas('vehicle', function($q) {
            $q->where('status', Vehicle::STATUS_RENT); // filter kendaraan sedang disewa
        })
        ->get();
}

    public function find($id)
    {
        return RentCar::with(['vehicle', 'paymentAmount'])
                        ->findOrFail($id);
    }

    public function store(Request $req)
    {
        return $this->runInTransaction(function () use ($req) {
            $pricePerDay = $this->normalizeDecimal($req->input('pricePerDay'));
           
            $rentCar = RentCar::create([
                'vehicle_id'       => $req->input('vehicle_id'),
                'renter_name'      => $req->input('renter_name'),
                'renter_address'   => $req->input('renter_address'),
                'renter_phone'     => $req->input('renter_phone'),
                'startDate'        => $req->input('startDate'),
                'endDate'          => $req->input('endDate'),
                'pricePerDay'      => $req->input('pricePerDay'),
                'penalty'          => $req->input('penalty'),
                'notes'            => $req->input('notes'),
                'type'             => $req->input('type'), 
            ]);

            Vehicle::where('vehicleId', $req->input('vehicle_id'))
                ->update(['status' => Vehicle::STATUS_RENT]);

            $start = Carbon::parse($req->input('startDate'));
            $end   = Carbon::parse($req->input('endDate'));
            $days  = $start->diffInDays($end) + 1;
            $total = $days * $req->input('pricePerDay');

            if ($rentCar->type == RentCar::TYPE_CASH) {
                PaymentAmount::create([
                    'payable_id'   => $rentCar->rentCarId,
                    'payable_type' => RentCar::class,        
                    'date'         => $rentCar->startDate,
                    'type'         => PaymentAmount::TYPE_MASUK,
                    'amount'       => $total,
                    'status'       => PaymentAmount::STATUS_ACTIVE,
                ]);
            } elseif ($rentCar->type == RentCar::TYPE_HUTANG) {
                Debt::create([
                    'debtable_id'   => $rentCar->rentCarId,
                    'debtable_type' => RentCar::class,
                    'amount'        => $total,
                    'due_date'      => Carbon::now()->addMonth(), 
                    'status'        => 0, 
                ]);
            }

            return $rentCar;
        });
    }

    public function update(Request $req, $id)
    {
        return $this->runInTransaction(function () use ($req, $id) {
            $pricePerDay = $this->normalizeDecimal($req->input('pricePerDay'));
            $rentCar      = RentCar::findOrFail($id);
            $oldType      = $rentCar->type; 
            $oldVehicleId = $rentCar->vehicle_id; 
            $newVehicleId = $req->input('vehicle_id');

            $rentCar->update([
                'vehicle_id'    => $newVehicleId,
                'renter_name'   => $req->input('renter_name'),
                'renter_address'=> $req->input('renter_address'),
                'renter_phone'  => $req->input('renter_phone'),
                'startDate'     => $req->input('startDate'),
                'endDate'       => $req->input('endDate'),
                'pricePerDay'   => $req->input('pricePerDay'),
                'penalty'       => $req->input('penalty'),
                'notes'         => $req->input('notes'),
                'type'          => $req->input('type'),
            ]);

            if ($oldVehicleId != $newVehicleId) {
                Vehicle::where('vehicleId', $oldVehicleId)
                    ->update(['status' => Vehicle::STATUS_ACTIVE]);

                Vehicle::where('vehicleId', $newVehicleId)
                    ->update(['status' => Vehicle::STATUS_RENT]);
            }

            $start   = Carbon::parse($rentCar->startDate);
            $end     = Carbon::parse($rentCar->endDate);
            $days    = $start->diffInDays($end) + 1;
            $total   = $days * $rentCar->pricePerDay;

            if ($rentCar->type == RentCar::TYPE_CASH) {
                $payment = PaymentAmount::where('payable_id', $rentCar->rentCarId)
                    ->where('payable_type', RentCar::class)
                    ->first();

                if ($payment) {
                    $payment->update(['amount' => $total]);
                } else {
                    PaymentAmount::create([
                        'payable_id'   => $rentCar->rentCarId,
                        'payable_type' => RentCar::class,
                        'type'         => PaymentAmount::TYPE_MASUK,
                        'amount'       => $total,
                        'status'       => PaymentAmount::STATUS_ACTIVE,
                    ]);
                }
            } elseif ($rentCar->type == RentCar::TYPE_HUTANG) {
                Debt::create([
                    'debtable_id'   => $rentCar->rentCarId,
                    'debtable_type' => RentCar::class,
                    'amount'        => $total,
                    'due_date'      => Carbon::now()->addMonth(), 
                    'status'        => 0, 
                ]);
            }

            return $rentCar;
        });
    }

    public function delete($id)
    {
        return $this->runInTransaction(function () use ($id) {
            $rentCar         = RentCar::findOrFail($id);
            $rentCar->status = RentCar::STATUS_INACTIVE;
            $rentCar->save();
            PaymentAmount::where('payable_id', $rentCar->rentCarId)
                ->where('payable_type', RentCar::class)
                ->update(['status' => PaymentAmount::STATUS_INACTIVE]);
            Debt::where('debtable_id', $rentCar->rentCarId)
                            ->where('debtable_type', RentCar::class)
                            ->delete();

            Vehicle::where('vehicleId', $rentCar->vehicle_id)
                ->update(['status' => Vehicle::STATUS_ACTIVE]); 

            return $rentCar;
        });
    }

    private function normalizeDecimal($value)
    {
        if (!$value) return 0;
        $value = str_replace('.', '', $value); 
        $value = str_replace(',', '.', $value); 
        return floatval($value);
    }
}
