<?php

namespace App\Services\People\Supervisor;

use App\{
    Repositories\People\Supervisor\SupervisorRepositoryInterface,
    Traits\ApiResponse,
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};

use Throwable;

class SupervisorService
{
    use ApiResponse;

    public function __construct(protected SupervisorRepositoryInterface $supervisorRepo) {}

    public function index()
    {
        return $this->successResponse([
            'supervisor' => $this->supervisorRepo->getAll()
        ]);
    }

    public function show($id)
    {
        return $this->successResponse([
            'supervisor' => $this->supervisorRepo->find($id)
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
            $supervisor = $this->supervisorRepo->store($req);
            return $this->successResponse(['supervisor' => $supervisor], 'Supervisor created');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to create supervisor: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $req, $id)
    {
        $supervisor = $this->supervisorRepo->find($id); 

        $validator = Validator::make($req->all(), [
            'email'    => 'sometimes|email|unique:users,email,' . $supervisor->user->id,
            'password' => 'sometimes|min:6', 
            'name'     => 'sometimes|string|max:75',
            'phone'    => 'sometimes',
            'photo'    => 'sometimes|image|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $supervisor = $this->supervisorRepo->update($req, $id);
            return $this->successResponse(['supervisor' => $supervisor], 'Supervisor updated');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to update supervisor: ' . $e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        $this->supervisorRepo->delete($id);
        return $this->successResponse([], 'Supervisor deleted');
    }
}
