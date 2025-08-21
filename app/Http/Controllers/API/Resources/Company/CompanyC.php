<?php

namespace App\Http\Controllers\API\Resources\Company;

use App\{
    Http\Controllers\Controller,
    Services\Resources\Company\CompanyService
};

use Illuminate\{
    Http\Request
};

class CompanyC extends Controller
{
    public function __construct(protected CompanyService $service) {}

    public function index() { return $this->service->index(); }
    public function update(Request $req) { return $this->service->update($req); }
}
