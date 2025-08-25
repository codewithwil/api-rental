<?php

namespace App\Repositories\Resources\VehicleDepreciate;

use Illuminate\Http\Request;

interface VehicleDepreciateRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function store(Request $req);
    public function update(Request $req, $id);
    public function delete($id);
}
