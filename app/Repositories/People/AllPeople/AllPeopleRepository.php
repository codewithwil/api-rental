<?php

namespace App\Repositories\People\AllPeople;

use App\{
    Models\User
};

class AllPeopleRepository implements AllPeopleRepositoryInterface
{
    public function getAll()
    {
        $perPage = 10;
        $page = request()->get('page', 1);

        $users = User::with(['roles'])->get();

        $mapped = $users->map(function ($user) {
            return [
                'email'             => $user->email,
                'level'             => $user->roles->pluck('name')->first(),
                'last_login_ip'     => $user->last_login_ip,
                'last_login_device' => $user->last_login_device,
                'last_active_at'    => $user->last_active_at,
            ];
        });

        $paginated = \Illuminate\Pagination\LengthAwarePaginator::make(
            $mapped->forPage($page, $perPage),
            $mapped->count(),
            $perPage,
            $page
        );

        return $paginated;
    }

}
