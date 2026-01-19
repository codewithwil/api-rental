<?php

namespace App\Http\Controllers\API\Transactions\Debts;

use App\{
    Http\Controllers\Controller,
    Services\Transactions\Debts\DebtsService
};

use Illuminate\{
    Http\Request
};


class DebtsC extends Controller
{
    public function __construct(protected DebtsService $service) {}

    public function index(Request $req) { return $this->service->index($req); }
}
