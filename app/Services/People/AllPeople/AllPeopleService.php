<?php

namespace App\Services\People\AllPeople;

use App\{
    Repositories\People\AllPeople\AllPeopleRepositoryInterface,
    Traits\ApiResponse,
};

class AllPeopleService
{
    use ApiResponse;

    public function __construct(protected AllPeopleRepositoryInterface $allPeople) {}

    public function index()
    {
        return $this->successResponse([
            'allPeople' => $this->allPeople->getAll()
        ]);
    }
}
