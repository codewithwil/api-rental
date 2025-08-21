<?php

namespace App\Repositories\Resources\Vehicle;

use Illuminate\Http\Request;

interface VehicleRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function store(Request $req);
    public function update(Request $req, $id);
    public function delete($id);
}
