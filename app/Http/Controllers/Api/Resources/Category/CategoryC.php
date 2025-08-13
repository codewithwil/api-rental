<?php

namespace App\Http\Controllers\API\Resources\Category;

use App\{
    Http\Controllers\Controller,
    Services\Resources\Category\CategoryService
};

use Illuminate\{
    Http\Request
};

class CategoryC extends Controller
{
    public function __construct(protected CategoryService $service) {}

    public function index() { return $this->service->index(); }
    public function show($id) { return $this->service->show($id); }
    public function store(Request $req) { return $this->service->store($req); }
    public function update(Request $req, $id) { return $this->service->update($req, $id); }
    public function destroy($id) { return $this->service->delete($id); }
}
