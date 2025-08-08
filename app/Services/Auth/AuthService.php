<?php

namespace App\Services\Auth;

use App\{
    Repositories\Auth\AuthRepositoryInterface,
    Traits\ApiResponse
};

use Illuminate\{
    Http\Request
};

class AuthService
{
    use ApiResponse;
    public function __construct(protected AuthRepositoryInterface $authRepo){}

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $login = $this->authRepo->login($request);

        if (!$login) {
            return $this->errorMessage('Invalid credentials', 401);
        }

        return $this->successResponse([
            'user'  => $login['user'],
            'token' => $login['token'],
        ], 'Login successful');
    }

    public function logout()
    {
        $this->authRepo->logout();
        return $this->successResponse([], 'Logout successful');
    }
}
