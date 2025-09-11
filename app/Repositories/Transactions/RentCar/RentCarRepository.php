<?php

namespace App\Repositories\Transactions\RentCar;

use App\{
    Repositories\Transactions\RentCar\RentCarRepositoryInterface,
    Models\Transaction\RentCar\RentCar,
    Traits\DbTransaction,
    Models\Resources\Vehicle\Vehicle,
    Models\Transactions\Payment\PaymentAmount\PaymentAmount
};

use Illuminate\{
    Http\Request
};

use Carbon\{
    Carbon
};

class RentCarRepository implements RentCarRepositoryInterface
{
    use DbTransaction;

    public function getAll()
    {
        return RentCar::with(['vehicle'])->get();
    }

    public function find($id)
    {
        return RentCar::with(['vehicle', 'paymentAmount'])
                        ->findOrFail($id);
    }

    public function store(Request $req)
    {
        return $this->runInTransaction(function () use ($req) {
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
            ]);
            
            Vehicle::where('vehicleId', $req->input('vehicle_id'))
                ->update(['status' => Vehicle::STATUS_RENT]);

            $start = Carbon::parse($req->input('startDate'));
            $end   = Carbon::parse($req->input('endDate'));
            $days  = $start->diffInDays($end) + 1; 
            $total = $days * $req->input('pricePerDay');

            PaymentAmount::create([
                'payable_id'   => $rentCar->rentCarId,
                'payable_type' => RentCar::class,        
                'type'         => PaymentAmount::TYPE_MASUK,
                'amount'       => $total,
                'status'       => PaymentAmount::STATUS_ACTIVE,
            ]);

            return $rentCar;
        });
    }

    public function update(Request $req, $id)
    {
        return $this->runInTransaction(function () use ($req, $id) {
            $rentCar      = RentCar::findOrFail($id);
            $oldVehicleId = $rentCar->vehicle_id; 
            $newVehicleId = $req->input('vehicle_id');

            $rentCar->update($req->only([
                'vehicle_id','renter_name','renter_address','renter_phone',
                'startDate', 'endDate', 'pricePerDay', 'penalty','notes',
            ]));

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

            return $rentCar;
        });
    }
}
