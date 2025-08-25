<?php

namespace App\Repositories\Resources\VehicleDepreciate;

use App\{
    Repositories\Resources\VehicleDepreciate\VehicleDepreciateRepositoryInterface,
    Models\Resources\Vehicle\VehicleDepreciat,
    Traits\DbTransaction,
};

use Illuminate\{
    Http\Request
};

class VehicleDepreciateRepository implements VehicleDepreciateRepositoryInterface
{
    use DbTransaction;

    public function getAll()
    {
        return VehicleDepreciat::with(['vehicle'])->get();
    }

    public function find($id)
    {
        return VehicleDepreciat::with(['vehicle'])
                        ->findOrFail($id);
    }

    public function store(Request $req)
    {
        return $this->runInTransaction(function () use ($req) {
            $vehicleDepreciate = VehicleDepreciat::create([
                'vehicle_id'            => $req->input('vehicle_id'),
                'year'                  => $req->input('year'),
                'depreciation_amount'   => $req->input('depreciation_amount'),
                'book_value'            => $req->input('book_value'),
            ]);

            return $vehicleDepreciate;
        });
    }

    public function update(Request $req, $id)
    {
        return $this->runInTransaction(function () use ($req, $id) {
            $vehicleDepreciate = VehicleDepreciat::findOrFail($id);

            $vehicleDepreciate->update($req->only([
                'vehicle_id','year','depreciation_amount','book_value',
            ]));

            return $vehicleDepreciate;
        });
    }

    public function delete($id)
    {
        return $this->runInTransaction(function () use ($id) {
            $vehicleDepreciate = VehicleDepreciat::findOrFail($id);
            $vehicleDepreciate->delete();

            return $vehicleDepreciate;
        });
    }
}
