<?php

namespace App\Services\People\Admin;

use App\{
    Repositories\People\Admin\AdminRepositoryInterface,
    Traits\ApiResponse,
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};

use Throwable;

class AdminService
{
    use ApiResponse;

    public function __construct(protected AdminRepositoryInterface $adminRepo) {}

    public function index()
    {
        return $this->successResponse([
            'admins' => $this->adminRepo->getAll()
        ]);
    }

    public function show($id)
    {
        return $this->successResponse([
            'admin' => $this->adminRepo->find($id)
        ]);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'name'     => 'required|string|max:75',
            'phone'    => 'required',
            'photo'    => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $admin = $this->adminRepo->store($req);
            return $this->successResponse(['admin' => $admin], 'Admin created');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to create admin: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $req, $id)
    {
        $admin = $this->adminRepo->find($id); 

        $validator = Validator::make($req->all(), [
            'email'    => 'sometimes|email|unique:users,email,' . $admin->user->id,
            'password' => 'sometimes|min:6', 
            'name'     => 'sometimes|string|max:75',
            'phone'    => 'sometimes',
            'photo'    => 'sometimes|image|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $admin = $this->adminRepo->update($req, $id);
            return $this->successResponse(['admin' => $admin], 'Admin updated');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to update admin: ' . $e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        $this->adminRepo->delete($id);
        return $this->successResponse([], 'Admin deleted');
    }
}
