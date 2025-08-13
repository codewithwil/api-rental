<?php

namespace App\Repositories\People\Employee;

use App\{
    Models\People\Employee\Employee,
    Models\User,
    Traits\HasFileUpload,
    Traits\DbTransaction
};

use Illuminate\{
    Http\Request,
    Support\Facades\Hash
};

class EmployeeRepository implements EmployeeRepositoryInterface
{
    use HasFileUpload, DbTransaction;

    public function getAll()
    {
        return Employee::with('file', 'user')->get();
    }

    public function find($id)
    {
        return Employee::with('file', 'user')->findOrFail($id);
    }

    public function store(Request $req)
    {
        return $this->runInTransaction(function () use ($req) {
            $user = User::create([
                'email'    => $req->input('email'),
                'password' => Hash::make($req->input('password')),
            ]);
            $user->assignRole('employee');

            $employee = Employee::create([
                'user_id'   => $user->id,
                'name'      => $req->input('name'),
                'phone'     => $req->input('phone'),
                'address'   => $req->input('address'),
                'gender'    => $req->input('gender'),
                'birthdate' => $req->input('birthdate'),
                'hire_date' => $req->input('hire_date'),
                'salary'    => $req->input('salary'),
            ]);

            if ($req->hasFile('photo')) {
                $this->uploadFile($req->file('photo'), $employee, 'public', 'images/employee');
            }

            return $employee->load('file', 'user');
        });
    }


    public function update(Request $req, $id)
    {
        $employee = Employee::with('user')->findOrFail($id);

        $employee->update($req->only([
            'name', 'phone', 'address', 'gender', 'birthdate', 'hire_date', 'salary'
        ]));

        if ($req->filled('email') && $req->input('email') !== $employee->user->email) {
            $employee->user->email = $req->input('email');
        }

        if ($req->filled('password')) {
            $employee->user->password = Hash::make($req->input('password'));
        }

        $employee->user->save();

        if ($req->hasFile('photo')) {
            $this->uploadFile($req->file('photo'), $employee, 'public', 'images/employee');
        }

        return $employee->load('file', 'user');
    }


    public function delete($id)
    {
        $employee = Employee::with('user', 'file')->findOrFail($id);
        if ($employee->file) {
            $employee->file->delete();
        }

        $employee->user?->delete();
        return $employee->delete();
    }
}
