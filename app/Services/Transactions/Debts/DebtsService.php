<?php

namespace App\Services\Transactions\Debts;

use App\{
    Repositories\Transactions\Debts\DebtsRepositoryInterface,
    Traits\ApiResponse
};

use Illuminate\{
    Http\Request,
};

class DebtsService
{
    use ApiResponse;

    public function __construct(
        protected DebtsRepositoryInterface $debtsRepo
    ) {}

    public function index(Request $req)
    {
        return $this->successResponse([
            'debts' => $this->debtsRepo->getAll($req)
        ]);
    }
}
