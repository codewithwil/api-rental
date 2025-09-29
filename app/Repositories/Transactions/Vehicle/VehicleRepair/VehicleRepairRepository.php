<?php

namespace App\Repositories\Transactions\Vehicle\VehicleRepair;

use App\{
    Repositories\Transactions\Vehicle\VehicleRepair\VehicleRepairRepositoryInterface,
    Traits\DbTransaction,
    Models\Transactions\Vehicle\VehicleRepair\VehicleRepair
};
use App\Models\Resources\Vehicle\Vehicle;
use Illuminate\{
    Http\Request,
};
use Illuminate\Support\Facades\Auth;

class VehicleRepairRepository implements VehicleRepairRepositoryInterface
{
    use DbTransaction;

    public function getAll()
    {
        return VehicleRepair::with('vehicle')->where('status', VehicleRepair::STATUS_ACTIVE)->get();
    }

    public function getTypeApprove()
    {
        return VehicleRepair::with('vehicle')->where('statusRepair', VehicleRepair::STATUSREP_COMPLETED)->get();
    }

    public function find($id)
    {
        return VehicleRepair::with(['photo', 'user', 'vehicle'])->findOrFail($id);
    }

    public function updateStatus(Request $req, $id)
    {
        return $this->runInTransaction(function () use ($req, $id) {
            $vehicleRepair = VehicleRepair::with('vehicle')->findOrFail($id);
            $statusRepair  = (int) $req->input('statusRepair');
            $vehicleRepair->statusRepair = $statusRepair;
            $vehicleRepair->save();

            if ($statusRepair === VehicleRepair::STATUSREP_COMPLETED && $vehicleRepair->vehicle) {
                $vehicleRepair->vehicle->update([
                    'status' => Vehicle::STATUS_REPAIR
                ]);
            }

            return $vehicleRepair->load('vehicle');
        });
    }


    public function store(Request $req)
    {
        return $this->runInTransaction(function () use ($req) {
            $userId = Auth::id();
            $vehicleRepair = VehicleRepair::create([
                'vehicle_id'     => $req->input('vehicle_id'),
                'user_id'        => $userId,
                'submission_date'=> $req->input('submission_date'),
                'description'    => $req->input('description'),
                'statusRepair'   => $req->input('statusRepair', VehicleRepair::STATUSREP_PENDING),
                'estimated_cost' => $req->input('estimated_cost'),
                'status'         => VehicleRepair::STATUSREP_PENDING,
            ]);

            if ($req->hasFile('photos')) {
                foreach ($req->file('photos') as $photo) {
                    $vehicleRepair->uploadFile($photo, $vehicleRepair);
                }
            }

            return $vehicleRepair;
        });
    }

    public function update(Request $req, $id)
    {
        return $this->runInTransaction(function () use ($req, $id) {
            $vehicleRepair = VehicleRepair::findOrFail($id);
            $vehicleRepair->update($req->only([
                'vehicle_id', 'submission_date', 'description', 'statusRepair', 'estimated_cost'
            ]));

            if ($req->hasFile('photos')) {
                foreach ($req->file('photos') as $photo) {
                    $vehicleRepair->uploadFile($photo, $vehicleRepair);
                }
            }

            return $vehicleRepair;
        });
    }

    public function delete($id)
    {
        return $this->runInTransaction(function () use ($id) {
            $vehicleRepair         = VehicleRepair::with('vehicle')->findOrFail($id);
            $vehicleRepair->status = VehicleRepair::STATUS_INACTIVE;
            $vehicleRepair->save();

            if ($vehicleRepair->vehicle) {
                $vehicleRepair->vehicle->update([
                    'status' => Vehicle::STATUS_ACTIVE
                ]);
            }

            return $vehicleRepair->load('vehicle');
        });
    }

}
