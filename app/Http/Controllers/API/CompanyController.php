<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Resources\Company\Company;

class CompanyController extends Controller
{
    public function index()
    {
        return response()->json(Company::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'contact_number' => 'required|string',
        ]);

        $company = Company::create($request->all());

        return response()->json($company, 201);
    }

    public function show($id)
    {
        $company = Company::findOrFail($id);
        return response()->json($company);
    }

    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $company->update($request->all());

        return response()->json($company);
    }

    public function destroy($id)
    {
        Company::destroy($id);
        return response()->json(null, 204);
    }
}
