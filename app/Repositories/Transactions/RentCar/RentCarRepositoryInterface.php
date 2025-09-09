<?php

namespace App\Repositories\Transactions\RentCar;

use Illuminate\Http\Request;

interface RentCarRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function store(Request $req);
    public function update(Request $req, $id);
    public function delete($id);
}
