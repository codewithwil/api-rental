<?php

namespace App\Services\Resources\Brand;

use App\{
    Repositories\Resources\Brand\BrandRepositoryInterface,
    Traits\ApiResponse,
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};

use Throwable;

class BrandService
{
    use ApiResponse;

    public function __construct(protected BrandRepositoryInterface $brandRepo) {}

    public function index()
    {
        return $this->successResponse([
            'brand' => $this->brandRepo->getAll()
        ]);
    }

    public function show($id)
    {
        return $this->successResponse([
            'brand' => $this->brandRepo->find($id)
        ]);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string|max:75',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $brand = $this->brandRepo->store($req);
            return $this->successResponse(['brand' => $brand], 'Brand created');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to create brand: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $req, $id)
    {
        $brand = $this->brandRepo->find($id); 

        $validator = Validator::make($req->all(), [
            'name' => 'sometimes|string|max:75',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $brand = $this->brandRepo->update($req, $id);
            return $this->successResponse(['brand' => $brand], 'Brand updated');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to update brand: ' . $e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        $this->brandRepo->delete($id);
        return $this->successResponse([], 'Brand deleted');
    }
}
