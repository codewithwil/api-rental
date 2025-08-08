<?php

namespace App\Repositories\People\Supervisor;

use Illuminate\Http\Request;

interface SupervisorRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function store(Request $req);
    public function update(Request $req, $id);
    public function delete($id);
}
