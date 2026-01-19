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
    const STATUS_BELUMDIBAYAR = 0;
    const STATUS_LUNAS        = 1;
    protected $table          = 'debts';
    protected $primaryKey     = 'debtId';
    protected $fillable       = [
        'amount', 'due_date', 'status', 'debtable_id', 'debtable_type',
    ];

    public function debtables(){return $this->morphTo();}
}
