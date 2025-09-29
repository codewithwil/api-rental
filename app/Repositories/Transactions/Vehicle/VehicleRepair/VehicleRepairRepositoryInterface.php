<?php

namespace App\Repositories\Transactions\Vehicle\VehicleRepair;

use Illuminate\Http\Request;

interface VehicleRepairRepositoryInterface
{
    public function getAll();
    public function getTypeApprove();
    public function updateStatus(Request $req, $id);
    public function find($id);
    public function store(Request $req);
    public function update(Request $req, $id);
    public function delete($id);
}
