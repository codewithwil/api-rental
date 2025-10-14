<?php

namespace App\Http\Controllers\API\Transactions\RentCar;

use App\{
    Http\Controllers\Controller,
    Services\Transactions\RentCar\RentCarService
};

use Illuminate\{
    Http\Request
};

class RentCarC extends Controller
{
    public function __construct(protected RentCarService $service) {}

    public function index() { return $this->service->index(); }
    public function getSelected() { return $this->service->getSelected(); }
    public function show($id) { return $this->service->show($id); }
    public function store(Request $req) { return $this->service->store($req); }
    public function update(Request $req, $id) { return $this->service->update($req, $id); }
    public function delete($id) { return $this->service->delete($id); }
}
