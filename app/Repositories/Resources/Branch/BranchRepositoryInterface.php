<?php

namespace App\Repositories\Resources\Branch;

use Illuminate\Http\Request;

interface BranchRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function store(Request $req);
    public function update(Request $req, $id);
    public function delete($id);
}
