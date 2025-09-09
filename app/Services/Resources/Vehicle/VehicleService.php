<?php

namespace App\Services\Resources\Vehicle;

use App\{
    Repositories\Resources\Vehicle\VehicleRepositoryInterface,
    Traits\ApiResponse
};
use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};
use Throwable;

class VehicleService
{
    use ApiResponse;

    public function __construct(protected VehicleRepositoryInterface $vehicleRepo) {}

    public function index()
    {
        return $this->successResponse([
            'vehicles' => $this->vehicleRepo->getAll()
        ]);
    }
    
    public function selected()
    {
        return $this->successResponse([
            'vehicles' => $this->vehicleRepo->getSelected()
        ]);
    }

    public function show($id)
    {
        return $this->successResponse([
            'vehicle' => $this->vehicleRepo->find($id)
        ]);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'user_id'          => 'required|exists:users,id',
            'branch_id'        => 'required|exists:branches,branchId',
            'category_id'      => 'required|exists:categories,categoryId',
            'brand_id'         => 'required|exists:brands,brandId',
            'name'             => 'required|string|max:75',
            'plate_number'     => 'required|string|max:20|unique:vehicles,plate_number',
            'color'            => 'required|string|max:50',
            'year'             => 'required|digits:4|integer|min:1900',
            'acquisition_cost' => 'required|numeric|min:0',
            'kir_expiry_date'  => 'nullable|date',
            'stnk_date'        => 'nullable|date',
            'bpkb_date'        => 'nullable|date',
            'note'             => 'nullable|string|max:255',
            'photo'            => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $vehicle = $this->vehicleRepo->store($req);
            return $this->successResponse(['vehicle' => $vehicle], 'Vehicle created successfully');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to create vehicle: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'user_id'          => 'sometimes|exists:users,id',
            'branch_id'        => 'sometimes|exists:branches,branchId',
            'category_id'      => 'sometimes|exists:categories,categoryId',
            'brand_id'         => 'sometimes|exists:brands,brandId',
            'name'             => 'sometimes|string|max:75',
            'plate_number'     => "sometimes|string|max:20|unique:vehicles,plate_number,$id,vehicleId",
            'color'            => 'sometimes|string|max:50',
            'year'             => 'sometimes|digits:4|integer|min:1900',
            'acquisition_cost' => 'sometimes|numeric|min:0',
            'kir_expiry_date'  => 'nullable|date',
            'stnk_date'        => 'nullable|date',
            'bpkb_date'        => 'nullable|date',
            'note'             => 'nullable|string|max:255',
            'photo'            => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $vehicle = $this->vehicleRepo->update($req, $id);
            return $this->successResponse(['vehicle' => $vehicle], 'Vehicle updated successfully');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to update vehicle: ' . $e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $vehicle = $this->vehicleRepo->delete($id);
            return $this->successResponse(['vehicle' => $vehicle], 'Vehicle deleted successfully');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to delete vehicle: ' . $e->getMessage(), 500);
        }
    }
}
