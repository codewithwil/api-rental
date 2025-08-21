<?php

namespace App\Repositories\Resources\Company;

use Illuminate\Http\Request;

interface CompanyRepositoryInterface
{
    public function getAll();
    public function update(Request $req);
}
