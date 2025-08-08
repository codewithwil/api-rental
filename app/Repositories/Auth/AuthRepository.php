<?php

namespace App\Repositories\Auth;

use Illuminate\{
    Support\Facades\Auth,
    Support\Facades\Hash,
    Http\Request
};

use App\{
    Models\User
};

class AuthRepository implements AuthRepositoryInterface
{
    public function login(Request $req)
    {
        $user = User::where('email', $req->email)->first();

        if (!$user || !Hash::check($req->password, $user->password)) {
            return null;
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $user->update([
            'last_login_ip'     => $req->ip(),
            'last_login_device' => $req->header('User-Agent'),
            'last_active_at'    => now(),
        ]);

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    public function logout()
    {
        if ($user = Auth::user()) {
            $user->currentAccessToken()?->delete();
            $user->update([
                'last_active_at' => null,
            ]);
        }
        return true;
    }
}
