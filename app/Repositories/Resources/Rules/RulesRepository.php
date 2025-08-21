<?php

namespace App\Repositories\Resources\Rules;

use App\{
    Repositories\Resources\Rules\RulesRepositoryInterface,
    Models\Resources\Rules\Rules,
    Traits\DbTransaction,
};

use Illuminate\{
    Http\Request,
};

class RulesRepository implements RulesRepositoryInterface
{
    use DbTransaction;
    public function getAll()
    {
        $rules = Rules::first();
        if (!$rules) return null;

        $content = collect(explode("\n", $rules->content))
            ->map(fn($line) => trim($line))
            ->filter() 
            ->implode("\n");
        return [
            'rulesId' => $rules->rulesId,
            'content' => $content,
            'created_at' => $rules->created_at,
            'updated_at' => $rules->updated_at,
        ];
    }


    public function update(Request $req)
    {
        return $this->runInTransaction(function () use ($req) {
            $rules = Rules::first();
            $rules->update($req->only(['content']));
            return $rules;
        });
    }
}
