<?php

namespace App\Services\Transactions\ReturnRentCar;

use App\{
    Repositories\Transactions\ReturnRentCar\ReturnRentCarRepositoryInterface,
    Traits\ApiResponse
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};

use Throwable;

class ReturnRentCarService
{
    use ApiResponse;

    public function __construct(
        protected ReturnRentCarRepositoryInterface $returnRentCarRepo
    ) {}

    public function index()
    {
        return $this->successResponse([
            'returnRentCars' => $this->returnRentCarRepo->getAll()
        ]);
    }
       
    public function show($id)
    {
        return $this->successResponse([
            'returnRentCar' => $this->returnRentCarRepo->find($id)
        ]);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'rentCar_id'     => 'required|exists:rent_cars,rentCarId',
            'return_date'    => 'required|date',
            'return_name'    => 'required|string|max:75',
            'return_address' => 'required|string',
            'return_phone'   => 'required|string|max:20',
            'notes'          => 'nullable|string',
            'type'           => 'nullable|in:1,2',
            'due_date'       => 'nullable|date|after:return_date',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $return = $this->returnRentCarRepo->store($req);
            return $this->successResponse(['returnRentCar' => $return], 'Return Rent Car created successfully');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to create returnRentCar: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'return_date'    => 'sometimes|date',
            'return_name'    => 'sometimes|string|max:75',
            'return_address' => 'sometimes|string',
            'return_phone'   => 'sometimes|string|max:20',
            'notes'          => 'nullable|string',
            'type'           => 'sometimes|in:1,2',
            'due_date'       => 'nullable|date|after:return_date',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $return = $this->returnRentCarRepo->update($req, $id);
            return $this->successResponse(['returnRentCar' => $return], 'Return Rent Car updated successfully');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to update returnRentCar: ' . $e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $this->returnRentCarRepo->delete($id);
            return $this->successResponse([], 'Return Rent Car deleted successfully');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to delete returnRentCar: ' . $e->getMessage(), 500);
        }
    }
}
