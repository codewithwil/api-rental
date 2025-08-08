<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    public function index()
    {
        return response()->json(Employee::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'branch_id' => 'required|exists:branches,id',
            'position' => 'required|string',
        ]);

        $employee = Employee::create($request->all());

        return response()->json($employee, 201);
    }

    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        return response()->json($employee);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->update($request->all());

        return response()->json($employee);
    }

    public function destroy($id)
    {
        Employee::destroy($id);
        return response()->json(null, 204);
    }
}