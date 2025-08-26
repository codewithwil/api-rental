<?php

namespace App\Repositories\People\AllPeople;

use App\{
    Models\User
};

class AllPeopleRepository implements AllPeopleRepositoryInterface
{

    public function getAll()
    {
        return User::with(['roles'])
            ->get()
            ->map(function ($user) {
                return [
                    'email'             => $user->email,
                    'level'             => $user->roles->pluck('name')->first(), 
                    'last_login_ip'     => $user->last_login_ip,
                    'last_login_device' => $user->last_login_device,
                    'last_active_at'    => $user->last_active_at,
                ];
            });
    }
}
