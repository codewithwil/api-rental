<?php

namespace App\Services\Transactions\Vehicle\VehicleRepairRealiz;

use App\{
    Traits\ApiResponse,
    Repositories\Transactions\Vehicle\VehicleRepairRealiz\VehicleRepairRealizRepositoryInterface
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};

use Throwable;

class VehicleRepairRealizService
{
    use ApiResponse;

    public function __construct(protected VehicleRepairRealizRepositoryInterface $vehicleRepairRealizRepo) {}

    public function index(Request $req)
    {
        return $this->successResponse([
            'vehicleRepairRealiz' => $this->vehicleRepairRealizRepo->getAll($req)
        ]);
    }

    public function show($id)
    {
        return $this->successResponse([
            'vehicleRepairRealiz' => $this->vehicleRepairRealizRepo->find($id)
        ]);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'vehicleRep_id' => 'required|integer|exists:vehicle_repairs,vehicleRepId',
            'completeDate'  => 'required|date',
            'amount'        => 'required|numeric|min:0',
            'notes'         => 'nullable|string|max:500',
            'photo'         => 'nullable|file|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $vehicleRepairRealiz = $this->vehicleRepairRealizRepo->store($req);
            return $this->successResponse(
                ['vehicleRepairRealiz' => $vehicleRepairRealiz],
                'Vehicle Repair Realization created'
            );
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to create Vehicle Repair Realization: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'vehicleRep_id' => 'sometimes|integer|exists:vehicle_repairs,vehicleRepId',
            'completeDate'  => 'sometimes|date',
            'amount'        => 'nullable|numeric|min:0',
            'notes'         => 'nullable|string|max:500',
            'photo'         => 'nullable|file|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $vehicleRepairRealiz = $this->vehicleRepairRealizRepo->update($req, $id);
            return $this->successResponse(
                ['vehicleRepairRealiz' => $vehicleRepairRealiz],
                'Vehicle Repair Realization updated'
            );
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to update Vehicle Repair Realization: ' . $e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $this->vehicleRepairRealizRepo->delete($id);
            return $this->successResponse([], 'Vehicle Repair Realization deleted');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to delete Vehicle Repair Realization: ' . $e->getMessage(), 500);
        }
    }
}
