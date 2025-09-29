<?php

namespace App\Repositories\Transactions\Vehicle\VehicleRepairRealiz;

use App\{
    Models\Transactions\Vehicle\VehicleRepairRealiz\VehicleRepairRealiz,
    Traits\DbTransaction
};
use App\Models\Transactions\Payment\PaymentAmount\PaymentAmount;
use Illuminate\Http\Request;

class VehicleRepairRealizRepository implements VehicleRepairRealizRepositoryInterface
{
    use DbTransaction;

    public function getAll()
    {
        return VehicleRepairRealiz::with(['vehicleRepair.vehicle', 'paymentAmount'])
            ->where('status', VehicleRepairRealiz::STATUS_ACTIVE)
            ->get();
    }

    public function find($id)
    {
        return VehicleRepairRealiz::with(['photo', 'paymentAmount', 'vehicleRepair.vehicle'])
            ->findOrFail($id);
    }

    public function store(Request $req)
    {
        return $this->runInTransaction(function () use ($req) {
            $vehicleRepairRealiz = VehicleRepairRealiz::create([
                'vehicleRep_id' => $req->input('vehicleRep_id'),
                'completeDate'  => $req->input('completeDate'),
                'notes'         => $req->input('notes'),
                'status'        => VehicleRepairRealiz::STATUS_ACTIVE,
            ]);

            if ($req->hasFile('photo')) {
                $vehicleRepairRealiz->uploadFile($req->file('photo'), $vehicleRepairRealiz);
            }
            
            if ($req->filled('amount')) {
                $vehicleRepairRealiz->paymentAmount()->create([
                    'type'         => PaymentAmount::TYPE_KELUAR, 
                    'amount'       => $req->input('amount'),
                    'status'       => PaymentAmount::STATUS_ACTIVE,
                ]);
            }

            return $vehicleRepairRealiz->load(['paymentAmount']);
        });
    }

    public function update(Request $req, $id)
    {
        return $this->runInTransaction(function () use ($req, $id) {
            $vehicleRepairRealiz = VehicleRepairRealiz::findOrFail($id);
            $vehicleRepairRealiz->update($req->only([
                'vehicleRep_id', 'completeDate', 'notes'
            ]));

            if ($req->hasFile('photo')) {
                $vehicleRepairRealiz->uploadFile($req->file('photo'), $vehicleRepairRealiz);
            }

             if ($req->filled('amount')) {
                $payment = $vehicleRepairRealiz->paymentAmount;
                if ($payment) {
                    $payment->update([
                        'amount' => $req->input('amount'),
                        'status' => PaymentAmount::STATUS_ACTIVE,
                    ]);
                } else {
                    $vehicleRepairRealiz->paymentAmount()->create([
                        'type'   => PaymentAmount::TYPE_KELUAR,
                        'amount' => $req->input('amount'),
                        'status' => PaymentAmount::STATUS_ACTIVE,
                    ]);
                }
            }

            return $vehicleRepairRealiz->load(['paymentAmount']);
        });
    }

   public function delete($id)
    {
        return $this->runInTransaction(function () use ($id) {
            $vehicleRepairRealiz = VehicleRepairRealiz::findOrFail($id);

            $vehicleRepairRealiz->update([
                'status' => VehicleRepairRealiz::STATUS_INACTIVE,
            ]);

            $vehicleRepairRealiz->paymentAmount()->update([
                'status' => PaymentAmount::STATUS_INACTIVE,
            ]);

            return $vehicleRepairRealiz;
        });
    }

}
