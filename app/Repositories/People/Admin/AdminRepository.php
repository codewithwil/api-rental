<?php

namespace App\Repositories\People\Admin;

use App\{
    Models\People\Admin\Admin,
    Models\User,
    Traits\HasFileUpload
};

use Illuminate\{
    Http\Request,
    Support\Facades\DB,
    Support\Facades\Hash
};

class AdminRepository implements AdminRepositoryInterface
{
    use HasFileUpload;

    public function getAll()
    {
        return Admin::with('file', 'user')->get();
    }

    public function find($id)
    {
        return Admin::with('file', 'user')->findOrFail($id);
    }

    public function store(Request $req)
    {
        return DB::transaction(function () use ($req) {
            $user = User::create([
                'email'    => $req->input('email'),
                'password' => Hash::make($req->input('password')),
            ]);
            $user->assignRole('admin');

            $admin = Admin::create([
                'user_id' => $user->id,
                'name'    => $req->input('name'),
                'phone'   => $req->input('phone'),
            ]);

            if ($req->hasFile('photo')) {
                $this->uploadFile($req->file('photo'), $admin, 'public', 'images/admin');
            }

            return $admin->load('file', 'user');
        });
    }


    public function update(Request $req, $id)
    {
        $admin = Admin::with('user')->findOrFail($id);

        $admin->update($req->only(['name', 'phone']));

        if ($req->filled('email') && $req->input('email') !== $admin->user->email) {
            $admin->user->email = $req->input('email');
        }

        if ($req->filled('password')) {
            $admin->user->password = Hash::make($req->input('password'));
        }

        $admin->user->save();

        if ($req->hasFile('photo')) {
            $this->uploadFile($req->file('photo'), $admin, 'public', 'images/admin');
        }

        return $admin->load('file', 'user');
    }


    public function delete($id)
    {
        $admin = Admin::with('user', 'file')->findOrFail($id);
        if ($admin->file) {
            $admin->file->delete();
        }

        $admin->user?->delete();
        return $admin->delete();
    }
}
