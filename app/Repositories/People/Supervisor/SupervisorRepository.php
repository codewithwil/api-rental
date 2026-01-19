<?php

namespace App\Repositories\People\Supervisor;

use App\{
    Repositories\People\Supervisor\SupervisorRepositoryInterface,
    Models\People\Supervisor\Supervisor,
    Models\User,
    Traits\HasFileUpload,
    Traits\DbTransaction
};

use Illuminate\{
    Http\Request,
    Support\Facades\Hash
};

class SupervisorRepository implements SupervisorRepositoryInterface
{
    use HasFileUpload, DbTransaction;

    public function getAll()
    {
        return Supervisor::with('file', 'user')->get();
    }

    public function find($id)
    {
        return Supervisor::with('file', 'user')->findOrFail($id);
    }

    public function store(Request $req)
    {
        return $this->runInTransaction(function () use ($req) {
            $user = User::create([
                'email'    => $req->input('email'),
                'password' => Hash::make($req->input('password')),
            ]);
            $user->assignRole('supervisor');

            $supervisor = Supervisor::create([
                'user_id' => $user->id,
                'name'    => $req->input('name'),
                'phone'   => $req->input('phone'),
            ]);

            if ($req->hasFile('photo')) {
                $this->uploadFile($req->file('photo'), $supervisor, 'public', 'images/supervisor');
            }

            return $supervisor->load('file', 'user');
        });
    }


    public function update(Request $req, $id)
    {
        $supervisor = Supervisor::with('user')->findOrFail($id);

        $supervisor->update($req->only(['name', 'phone']));

        if ($req->filled('email') && $req->input('email') !== $supervisor->user->email) {
            $supervisor->user->email = $req->input('email');
        }

        if ($req->filled('password')) {
            $supervisor->user->password = Hash::make($req->input('password'));
        }

        $supervisor->user->save();

        if ($req->hasFile('photo')) {
            $this->uploadFile($req->file('photo'), $supervisor, 'public', 'images/supervisor');
        }

        return $supervisor->load('file', 'user');
    }


    public function delete($id)
    {
        $supervisor = Supervisor::with('user', 'file')->findOrFail($id);

        if ($supervisor->file) {
            $supervisor->file->delete();
        }

        $user = $supervisor->user;
        $supervisor->delete();
        $user?->delete();

        return true;
    }

}
