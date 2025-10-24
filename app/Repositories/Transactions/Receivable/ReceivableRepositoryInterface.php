<?php

namespace App\Repositories\Transactions\Receivable;

use Illuminate\Http\Request;

interface ReceivableRepositoryInterface
{
    public function getAll(Request $req);
    public function payDebt(Request $req);
    public function find($id);
    public function update(Request $req, $id);
    public function delete($id);
}
