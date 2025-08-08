<?php

namespace App\Repositories\People\Employee;

use Illuminate\Http\Request;

interface EmployeeRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function store(Request $req);
    public function update(Request $req, $id);
    public function delete($id);
}
