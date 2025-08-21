<?php

namespace App\Repositories\Resources\Rules;

use Illuminate\Http\Request;

interface RulesRepositoryInterface
{
    public function getAll();
    public function update(Request $req);
}
