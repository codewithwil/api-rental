<?php

namespace App\Repositories\People\AllPeople;

use App\{
    Models\User
};

use Illuminate\{
    Pagination\LengthAwarePaginator
};

class AllPeopleRepository implements AllPeopleRepositoryInterface
{

    public function getAll()
    {
        $perPage = 10;
        $page = request()->get('page', 1);

        $users = User::with('roles')->get();

        $mapped = $users->map(function ($user) {
            return [
                'email'             => $user->email,
                'level'             => $user->roles->pluck('name')->first(),
                'last_login_ip'     => $user->last_login_ip,
                'last_login_device' => $user->last_login_device,
                'last_active_at'    => $user->last_active_at,
            ];
        });

        
        $currentPageItems = $mapped->forPage($page, $perPage);

        $paginated = new LengthAwarePaginator(
            $currentPageItems, 
            $mapped->count(),         
            $perPage,                 
            $page,                    
            ['path' => request()->url(), 'query' => request()->query()] 
        );

        return $paginated;
    }

}
