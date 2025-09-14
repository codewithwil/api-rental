<?php

namespace App\Models\Transactions\Payment\PaymentAmount;

use App\Traits\ActivityLogs;
use Illuminate\Database\Eloquent\Model;

class PaymentAmount extends Model
{
    use ActivityLogs;
    const TYPE_MASUK      = 1;
    const TYPE_KELUAR     = 2;
    protected $table      = 'payment_amounts';
    protected $primaryKey = 'payAmountId';
    protected $fillable   = [
        'payable_id', 'payable_type', 'type', 'amount', 'status'
    ];

    public function payable(){return $this->morphTo();}
}
