<?php

namespace App\Services\Resources\Branch;

use App\{
    Repositories\Resources\Branch\BranchRepositoryInterface,
    Traits\ApiResponse,
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};

use Throwable;

class BranchService
{
    use ApiResponse;

    public function __construct(protected BranchRepositoryInterface $branchRepo) {}

    public function index()
    {
        return $this->successResponse([
            'branches' => $this->branchRepo->getAll()
        ]);
    }

    public function show($id)
    {
        return $this->successResponse([
            'branch' => $this->branchRepo->find($id)
        ]);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'branchName'      => 'required|string|max:75',
            'address'         => 'sometimes|string|max:255',
            'email'           => 'sometimes|email|max:100',
            'operationalHours'=> 'sometimes|string|max:50',
            'phone'           => ['sometimes','regex:/^\+?[0-9]{7,15}$/'],
            'ltd'             => 'sometimes|numeric',
            'lng'             => 'sometimes|numeric',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $branch = $this->branchRepo->store($req);
            return $this->successResponse(['branch' => $branch], 'Branch created successfully');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to create branch: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'branchName'      => 'sometimes|string|max:75',
            'address'         => 'sometimes|string|max:255',
            'email'           => 'sometimes|email|max:100',
            'operationalHours'=> 'sometimes|string|max:50',
            'phone'           => ['sometimes','regex:/^\+?[0-9]{7,15}$/'],
            'ltd'             => 'sometimes|numeric',
            'lng'             => 'sometimes|numeric',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $branch = $this->branchRepo->update($req, $id);
            return $this->successResponse(['branch' => $branch], 'Branch updated successfully');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to update branch: ' . $e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $branch = $this->branchRepo->delete($id);
            return $this->successResponse(['branch' => $branch], 'Branch deleted successfully');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to delete branch: ' . $e->getMessage(), 500);
        }
    }
}
