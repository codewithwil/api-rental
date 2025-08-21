<?php

namespace App\Http\Controllers\API\People\AllPeople;

use App\{
    Http\Controllers\Controller,
    Services\People\AllPeople\AllPeopleService
};

class AllPeopleC extends Controller
{
    public function __construct(protected AllPeopleService $service) {}

    public function index() { return $this->service->index(); }
}
