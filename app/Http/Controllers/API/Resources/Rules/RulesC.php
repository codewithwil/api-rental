<?php

namespace App\Http\Controllers\API\Resources\Rules;

use App\{
    Http\Controllers\Controller,
    Services\Resources\Rules\RulesService
};

use Illuminate\{
    Http\Request
};

class RulesC extends Controller
{
    public function __construct(protected RulesService $service) {}

    public function index() { return $this->service->index(); }
    public function update(Request $req) { return $this->service->update($req); }
}
