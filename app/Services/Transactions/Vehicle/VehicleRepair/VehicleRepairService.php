<?php

namespace App\Services\Transactions\Vehicle\VehicleRepair;

use App\{
    Repositories\Transactions\Vehicle\VehicleRepair\VehicleRepairRepositoryInterface,
    Traits\ApiResponse,
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};

use Throwable;

class VehicleRepairService
{
    use ApiResponse;

    public function __construct(protected VehicleRepairRepositoryInterface $vehicleRepairRepo) {}

    public function index(Request $req)
    {
        return $this->successResponse([
            'vehicleRepair' => $this->vehicleRepairRepo->getAll($req)
        ]);
    }

    public function getTypeApprove()
    {
        return $this->successResponse([
            'vehicleRepair' => $this->vehicleRepairRepo->getTypeApprove()
        ]);
    }

    public function show($id)
    {
        return $this->successResponse([
            'vehicleRepair' => $this->vehicleRepairRepo->find($id)
        ]);
    }

    public function updateStatus(Request $req, $id)
    {
        $vehicleRepair = $this->vehicleRepairRepo->find($id);

        $validator = Validator::make($req->all(), [
            'statusRepair'  => 'sometimes|integer|in,3,4',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $vehicleRepair = $this->vehicleRepairRepo->updateStatus($req, $id);
            return $this->successResponse(['vehicleRepair' => $vehicleRepair], 'Vehicle Repair updated');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to update Vehicle Repair: ' . $e->getMessage(), 500);
        }
    }


    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'vehicle_id'     => 'required|integer',
            'submission_date'=> 'required|date',
            'description'    => 'required|string|max:255',
            'statusRepair'   => 'required|integer|in:1,2,3,4',
            'estimated_cost' => 'required|numeric',
            'photos'         => 'nullable|array',  
            'photos.*'       => 'file|mimes:jpeg,png,jpg,gif',  
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $vehicleRepair = $this->vehicleRepairRepo->store($req);
            return $this->successResponse(['vehicleRepair' => $vehicleRepair], 'Vehicle Repair created');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to create Vehicle Repair: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $req, $id)
    {
        $vehicleRepair = $this->vehicleRepairRepo->find($id);

        $validator = Validator::make($req->all(), [
            'vehicle_id'     => 'sometimes|integer',
            'submission_date'=> 'sometimes|date',
            'description'    => 'sometimes|string|max:255',
            'statusRepair'   => 'sometimes|integer|in:1,2,3,4',
            'estimated_cost' => 'sometimes|numeric',
            'photos'         => 'nullable|array',
            'photos.*'       => 'file|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $vehicleRepair = $this->vehicleRepairRepo->update($req, $id);
            return $this->successResponse(['vehicleRepair' => $vehicleRepair], 'Vehicle Repair updated');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to update Vehicle Repair: ' . $e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $this->vehicleRepairRepo->delete($id);
            return $this->successResponse([], 'Vehicle Repair deleted');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to delete Vehicle Repair: ' . $e->getMessage(), 500);
        }
    }
}
