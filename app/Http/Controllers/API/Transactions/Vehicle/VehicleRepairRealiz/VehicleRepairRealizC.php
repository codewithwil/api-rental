<?php

namespace App\Http\Controllers\API\Transactions\Vehicle\VehicleRepairRealiz;

use App\{
    Http\Controllers\Controller,
    Services\Transactions\Vehicle\VehicleRepairRealiz\VehicleRepairRealizService
};

use Illuminate\{
    Http\Request
};

class VehicleRepairRealizC extends Controller
{
    public function __construct(protected VehicleRepairRealizService $service) {}

    public function index(Request $req) { return $this->service->index($req); }
    public function show($id) { return $this->service->show($id); }
    public function store(Request $req) { return $this->service->store($req); }
    public function update(Request $req, $id) { return $this->service->update($req, $id); }
    public function delete($id) { return $this->service->delete($id); }
}
