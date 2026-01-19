<?php

namespace App\Repositories\Transactions\Vehicle\VehicleRepairRealiz;

use Illuminate\Http\Request;

interface VehicleRepairRealizRepositoryInterface
{
    public function getAll(Request $req);
    public function find($id);
    public function store(Request $req);
    public function update(Request $req, $id);
    public function delete($id);
}
