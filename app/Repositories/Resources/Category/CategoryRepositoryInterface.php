<?php

namespace App\Repositories\Resources\Category;

use Illuminate\Http\Request;

interface CategoryRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function store(Request $req);
    public function update(Request $req, $id);
    public function delete($id);
}
