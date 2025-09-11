<?php

namespace App\Services\Transactions\RentCar;

use App\{
    Repositories\Transactions\RentCar\RentCarRepositoryInterface,
    Traits\ApiResponse
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};
use Throwable;

class RentCarService
{
    use ApiResponse;

    public function __construct(
        protected RentCarRepositoryInterface $rentCarRepo
    ) {}

    public function index()
    {
        return $this->successResponse([
            'rentCar' => $this->rentCarRepo->getAll()
        ]);
    }

    public function show($id)
    {
        return $this->successResponse([
            'rentCar' => $this->rentCarRepo->find($id)
        ]);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'vehicle_id'     => 'required|exists:vehicles,vehicleId',
            'renter_name'    => 'required|string|max:75',
            'renter_address' => 'required|string',
            'renter_phone'   => 'required|string|max:20',
            'startDate'      => 'required|date|before_or_equal:endDate',
            'endDate'        => 'required|date|after_or_equal:startDate',
            'pricePerDay'    => 'required|numeric|min:0',
            'penalty'        => 'required|numeric|min:0',
            'notes'          => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $rentCar = $this->rentCarRepo->store($req);
            return $this->successResponse(['rentCar' => $rentCar], 'Rent Car created successfully');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to create rentCar: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'vehicle_id'     => 'required|exists:vehicles,vehicleId',
            'renter_name'    => 'sometimes|string|max:75',
            'renter_address' => 'sometimes|string',
            'renter_phone'   => 'sometimes|string|max:20',
            'startDate'      => 'sometimes|date|before_or_equal:endDate',
            'endDate'        => 'sometimes|date|after_or_equal:startDate',
            'pricePerDay'    => 'sometimes|numeric|min:0',
            'penalty'        => 'sometimes|numeric|min:0',
            'notes'          => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $rentCar = $this->rentCarRepo->update($req, $id);
            return $this->successResponse(['rentCar' => $rentCar], 'Rent Car updated successfully');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to update rentCar: ' . $e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $rentCar = $this->rentCarRepo->delete($id);
            return $this->successResponse(['rentCar' => $rentCar], 'Rent Car deleted successfully');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to delete rentCar: ' . $e->getMessage(), 500);
        }
    }
}
