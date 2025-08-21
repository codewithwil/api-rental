<?php

namespace App\Repositories\Resources\Branch;

use App\{
    Repositories\Resources\Branch\BranchRepositoryInterface,
    Models\Resources\Branch\Branch,
    Traits\DbTransaction,
    Models\Resources\Company\Company
};

use Illuminate\{
    Http\Request,
};

class BranchRepository implements BranchRepositoryInterface
{
    use DbTransaction;
    public function getAll()
    {
        return Branch::where('status', Branch::STATUS_ACTIVE)->get();
    }

    public function find($id)
    {
        return Branch::findOrFail($id);
    }
    
    public function store(Request $req)
    {
        return $this->runInTransaction(function () use ($req) {
            $company = Company::first();
            $branch  = Branch::create([
                'company_id'      => $company->companyId,
                'branchName'      => $req->input('branchName'),
                'address'         => $req->input('address'),
                'email'           => $req->input('email'),
                'operationalHours'=> $req->input('operationalHours'),
                'phone'           => $req->input('phone'),
                'ltd'             => $req->input('ltd'),
                'lng'             => $req->input('lng'),
            ]);

            return $branch;
        });
    }

    public function update(Request $req, $id)
    {
        return $this->runInTransaction(function () use ($req, $id) {
            $branch = Branch::findOrFail($id);
            $branch->update($req->only([
                'branchName', 'address', 'email', 'operationalHours', 'phone', 'ltd', 'lng'
            ]));
            return $branch;
        });
    }

    public function delete($id)
    {
        return $this->runInTransaction(function () use ($id) {
            $branch         = Branch::findOrFail($id);
            $branch->status = Branch::STATUS_INACTIVE;
            $branch->save();

            return $branch;
        });
    }
}
