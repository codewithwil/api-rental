<?php

namespace App\Services\Resources\VehicleDepreciate;

use App\{
    Repositories\Resources\VehicleDepreciate\VehicleDepreciateRepositoryInterface,
    Traits\ApiResponse
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};
use Throwable;

class VehicleDepreciateService
{
    use ApiResponse;

    public function __construct(
        protected VehicleDepreciateRepositoryInterface $vehicleDepreciateRepo
    ) {}

    public function index()
    {
        return $this->successResponse([
            'vehicleDepreciate' => $this->vehicleDepreciateRepo->getAll()
        ]);
    }

    public function show($id)
    {
        return $this->successResponse([
            'vehicleDepreciate' => $this->vehicleDepreciateRepo->find($id)
        ]);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'vehicle_id'            => 'required|exists:vehicles,vehicleId',
            'year'                  => 'required|digits:4|integer|min:1900',
            'depreciation_amount'   => 'required|numeric|min:0',
            'book_value'            => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $vehicle = $this->vehicleDepreciateRepo->store($req);
            return $this->successResponse(['vehicle' => $vehicle], 'Vehicle Depreciate created successfully');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to create vehicle: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'vehicle_id'            => 'sometimes|exists:vehicles,vehicleId',
            'year'                  => 'sometimes|digits:4|integer|min:1900',
            'depreciation_amount'   => 'sometimes|numeric|min:0',
            'book_value'            => 'sometimes|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $vehicle = $this->vehicleDepreciateRepo->update($req, $id);
            return $this->successResponse(['vehicle' => $vehicle], 'Vehicle Depreciate updated successfully');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to update vehicle: ' . $e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $vehicle = $this->vehicleDepreciateRepo->delete($id);
            return $this->successResponse(['vehicle' => $vehicle], 'Vehicle Depreciate deleted successfully');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to delete vehicle: ' . $e->getMessage(), 500);
        }
    }
}
