<?php

namespace App\Services\People\Employee;

use App\{
    Repositories\People\Employee\EmployeeRepositoryInterface,
    Traits\ApiResponse,
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};
use Illuminate\Validation\Rule;
use Throwable;

class EmployeeService
{
    use ApiResponse;

    public function __construct(protected EmployeeRepositoryInterface $employeeRepo) {}

    public function index()
    {
        return $this->successResponse([
            'employee' => $this->employeeRepo->getAll()
        ]);
    }

    public function show($id)
    {
        return $this->successResponse([
            'employee' => $this->employeeRepo->find($id)
        ]);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8',
            'name'       => 'required|string|max:75',
            'phone'      => 'required|string|max:20',
            'address'    => 'required|string|max:255',
            'gender'     => ['required', Rule::in([0, 1])], 
            'birthdate'  => 'required|date|before:today',
            'hire_date'  => 'required|date|before_or_equal:today',
            'salary'     => 'required|numeric|min:0',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $employee = $this->employeeRepo->store($req);
            return $this->successResponse(['employee' => $employee], 'Employee created');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to create employee: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $req, $id)
    {
        $employee = $this->employeeRepo->find($id); 

        $validator = Validator::make($req->all(), [
            'email'     => 'sometimes|email|unique:users,email,' . $employee->user->id,
            'password'  => 'sometimes|min:6', 
            'name'      => 'sometimes|string|max:75',
            'phone'     => 'sometimes|string|max:20',
            'address'   => 'sometimes|string|max:255',
            'gender'    => ['sometimes', Rule::in([0, 1])], 
            'birthdate' => 'sometimes|date|before:today',
            'hire_date' => 'sometimes|date|before_or_equal:today',
            'salary'    => 'sometimes|numeric|min:0',
            'photo'     => 'sometimes|image|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $employee = $this->employeeRepo->update($req, $id);
            return $this->successResponse(['employee' => $employee], 'Employee updated');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to update employee: ' . $e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        $this->employeeRepo->delete($id);
        return $this->successResponse([], 'Employee deleted');
    }
}
