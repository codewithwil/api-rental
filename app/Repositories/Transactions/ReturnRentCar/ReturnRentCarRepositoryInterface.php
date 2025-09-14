<?php

namespace App\Repositories\Transactions\ReturnRentCar;

use Illuminate\Http\Request;

interface ReturnRentCarRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function store(Request $req);
    public function update(Request $req, $id);
    public function delete($id);
}
