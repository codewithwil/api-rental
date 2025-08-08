<?php

namespace App\Http\Controllers\API\Auth;

use App\{
    Http\Controllers\Controller,
    Services\Auth\AuthService
};

use Illuminate\{
    Http\Request
};

class AuthC extends Controller
{
    public function __construct(protected AuthService $auth)
    {
        $this->auth = $auth;
    }

    public function login(Request $request)
    {
        return $this->auth->login($request);
    }

    public function logout()
    {
        return $this->auth->logout();
    }
}
