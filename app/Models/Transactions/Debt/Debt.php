<?php

namespace App\Models\Transactions\Debt;

use App\{
    Traits\ActivityLogs
};

use Illuminate\{
    Database\Eloquent\Model
};

class Debt extends Model
{
    use ActivityLogs;
    protected $table      = 'debts';
    protected $primaryKey = 'debtId';
    protected $fillable   = [
        'amount','due_date','status',
    ];

    public function debtables(){return $this->morphTo();}
}
