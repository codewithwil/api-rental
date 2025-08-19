<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Resources\Branch\Branch;

class BranchController extends Controller
{
    public function index()
    {
        return response()->json(Branch::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'company_id' => 'required|exists:companies,id',
            'location' => 'required|string',
        ]);

        $branch = Branch::create($request->all());

        return response()->json($branch, 201);
    }

    public function show($id)
    {
        $branch = Branch::findOrFail($id);
        return response()->json($branch);
    }

    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);
        $branch->update($request->all());

        return response()->json($branch);
    }

    public function destroy($id)
    {
        Branch::destroy($id);
        return response()->json(null, 204);
    }
}
