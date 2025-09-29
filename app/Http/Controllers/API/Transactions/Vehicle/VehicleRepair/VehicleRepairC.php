<?php

namespace App\Http\Controllers\API\Transactions\Vehicle\VehicleRepair;

use App\{
    Http\Controllers\Controller,
    Services\Transactions\Vehicle\VehicleRepair\VehicleRepairService
};

use Illuminate\{
    Http\Request
};

class VehicleRepairC extends Controller
{
    public function __construct(protected VehicleRepairService $service) {}

    public function index() { return $this->service->index(); }
    public function getTypeApprove() { return $this->service->getTypeApprove(); }
    public function updateStatus(Request $req, $id) { return $this->service->updateStatus($req, $id); }
    public function show($id) { return $this->service->show($id); }
    public function store(Request $req) { return $this->service->store($req); }
    public function update(Request $req, $id) { return $this->service->update($req, $id); }
    public function delete($id) { return $this->service->delete($id); }
}
