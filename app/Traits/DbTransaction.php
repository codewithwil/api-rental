<?php

namespace App\Traits;

use Closure;
use Illuminate\{
    Support\Facades\DB,
};

trait DbTransaction
{
    public function runInTransaction(Closure $callback): mixed
    {
        return DB::transaction(fn () => $callback());
    }
}
