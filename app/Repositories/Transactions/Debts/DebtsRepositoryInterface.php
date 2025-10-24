<?php

namespace App\Repositories\Transactions\Debts;

use Illuminate\Http\Request;

interface DebtsRepositoryInterface
{
    public function getAll(Request $req);
}
