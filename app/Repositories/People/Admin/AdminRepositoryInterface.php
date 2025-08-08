<?php

namespace App\Repositories\People\Admin;

use Illuminate\Http\Request;

interface AdminRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function store(Request $req);
    public function update(Request $req, $id);
    public function delete($id);
}
