<?php

namespace App\Http\Controllers\Api;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return response()->json(Admin::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:admins,email',
        ]);

        $admin = Admin::create($request->all());

        return response()->json($admin, 201);
    }

    public function show($id)
    {
        $admin = Admin::findOrFail($id);
        return response()->json($admin);
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->update($request->all());

        return response()->json($admin);
    }

    public function destroy($id)
    {
        Admin::destroy($id);
        return response()->json(null, 204);
    }
}