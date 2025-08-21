<?php

namespace App\Http\Controllers\API\Resources\Branch;

use App\{
    Http\Controllers\Controller,
    Services\Resources\Branch\BranchService,
};

use Illuminate\{
    Http\Request
};

class BranchC extends Controller
{
    public function __construct(protected BranchService $service) {}

    public function index() { return $this->service->index(); }
    public function show($id) { return $this->service->show($id); }
    public function store(Request $req) { return $this->service->store($req); }
    public function update(Request $req, $id) { return $this->service->update($req, $id); }
    public function delete($id) { return $this->service->delete($id); }
}
