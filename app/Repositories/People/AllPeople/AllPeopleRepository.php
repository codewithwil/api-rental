<?php

namespace App\Repositories\People\AllPeople;

use App\{
    Models\User
};

class AllPeopleRepository implements AllPeopleRepositoryInterface
{

    public function getAll()
    {
        return User::get();
    }
}
