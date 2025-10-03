<?php

namespace App\Repositories\Resources\Vehicle;

use App\{
    Repositories\Resources\Vehicle\VehicleRepositoryInterface,
    Models\Resources\Vehicle\Vehicle,
    Traits\DbTransaction,
    Traits\HasFileUpload,
    Models\Resources\Vehicle\VehicleDepreciat
};

use Illuminate\{
    Http\Request,
};

class VehicleRepository implements VehicleRepositoryInterface
{
    use DbTransaction, HasFileUpload;
    public function getAll()
    {
        return Vehicle::with([
            'file', 'user.admin','user.employee',
            'user.supervisor','brand','branch','category'
            ])
            ->where('status', '!=', Vehicle::STATUS_DELETED)
            ->get();
    }

    public function getSelected(){
          return Vehicle::with([
            'file', 'user.admin','user.employee',
            'user.supervisor','brand','branch','category'
            ])
            ->whereNotIn('status', [Vehicle::STATUS_DELETED, Vehicle::STATUS_RENT])
            ->get();
    }

    public function find($id)
    {
        return Vehicle::findOrFail($id);
    }

    public function store(Request $req)
    {
        return $this->runInTransaction(function () use ($req) {
            $vehicle = Vehicle::create([
                'user_id'           => $req->input('user_id'),
                'branch_id'         => $req->input('branch_id'),
                'category_id'       => $req->input('category_id'),
                'brand_id'          => $req->input('brand_id'),
                'name'              => $req->input('name'),
                'plate_number'      => $req->input('plate_number'),
                'color'             => $req->input('color'),
                'year'              => $req->input('year'),
                'acquisition_cost'  => $req->input('acquisition_cost'),
                'kir_expiry_date'   => $req->input('kir_expiry_date'),
                'stnk_date'         => $req->input('stnk_date'),
                'bpkb_date'         => $req->input('bpkb_date'),
                'note'              => $req->input('note'),
            ]);

            if ($req->hasFile('photo')) {
                $this->uploadFile($req->file('photo'), $vehicle, 'public', 'images/vehicle');
            }

            VehicleDepreciat::create([
                'vehicle_id'          => $vehicle->vehicleId,
                'year'                => $vehicle->year,
                'book_value'          => $vehicle->acquisition_cost,
                'depreciation_amount' => $vehicle->acquisition_cost,
            ]);

            return $vehicle;
        });
    }

    public function update(Request $req, $id)
    {
        return $this->runInTransaction(function () use ($req, $id) {
            $vehicle = Vehicle::findOrFail($id);

            $vehicle->update($req->only([
                'user_id','branch_id','category_id','brand_id','name',
                'plate_number','color','year','acquisition_cost','kir_expiry_date',
                'stnk_date','bpkb_date','note'
            ]));

            if ($req->hasFile('photo')) {
                $this->uploadFile($req->file('photo'), $vehicle, 'public', 'images/vehicle');
            }

            VehicleDepreciat::updateOrCreate(
                ['vehicle_id' => $vehicle->vehicleId, 'year' => $vehicle->year],
                [
                    'book_value'          => $vehicle->acquisition_cost,
                    'depreciation_amount' => $vehicle->acquisition_cost
                ]
            );

            return $vehicle;
        });
    }


    public function delete($id)
    {
        return $this->runInTransaction(function () use ($id) {
            $vehicle         = Vehicle::findOrFail($id);
            $vehicle->status = Vehicle::STATUS_DELETED;
            $vehicle->save();

            return $vehicle;
        });
    }
}
