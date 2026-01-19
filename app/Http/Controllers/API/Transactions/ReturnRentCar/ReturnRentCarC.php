<?php

namespace App\Http\Controllers\API\Transactions\ReturnRentCar;

use App\{
    Http\Controllers\Controller,
    Services\Transactions\ReturnRentCar\ReturnRentCarService
};

use Illuminate\{
    Http\Request
};

class ReturnRentCarC extends Controller
{
    public function __construct(protected ReturnRentCarService $service) {}

    public function index(Request $req) { return $this->service->index($req); }
    public function show($id) { return $this->service->show($id); }
    public function store(Request $req) { return $this->service->store($req); }
    public function update(Request $req, $id) { return $this->service->update($req, $id); }
    public function delete($id) { return $this->service->delete($id); }
}
