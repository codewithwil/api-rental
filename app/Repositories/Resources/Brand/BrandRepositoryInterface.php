<?php

namespace App\Repositories\Resources\Brand;

use Illuminate\Http\Request;

interface BrandRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function store(Request $req);
    public function update(Request $req, $id);
    public function delete($id);
}
