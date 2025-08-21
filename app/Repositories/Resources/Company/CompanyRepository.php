<?php

namespace App\Repositories\Resources\Company;

use App\{
    Repositories\Resources\Company\CompanyRepositoryInterface,
    Models\Resources\Company\Company,
    Traits\DbTransaction,
    Traits\HasFileUpload,
};

use Illuminate\{
    Http\Request,
};

class CompanyRepository implements CompanyRepositoryInterface
{
    use DbTransaction, HasFileUpload;
    public function getAll()
    {
        return Company::with('file')->first();
    }

    public function update(Request $req)
    {
        return $this->runInTransaction(function () use ($req) {
            $company = Company::first();
            $company->update($req->only(['name', 'web', 'phone', 'address']));
            if ($req->hasFile('photo')) {
                $this->uploadFile($req->file('photo'), $company, 'public', 'images/company');
            }
            return $company;
        });
    }
}
