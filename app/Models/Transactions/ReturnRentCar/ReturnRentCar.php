<?php

namespace App\Models\Transactions\ReturnRentCar;

use App\{
    Traits\ActivityLogs,
    Models\Transaction\RentCar\RentCar,
    Models\Transactions\Debt\Debt,
    Models\Transactions\Payment\PaymentAmount\PaymentAmount
};

use Illuminate\{
    Database\Eloquent\Model
};

class ReturnRentCar extends Model
{
    use ActivityLogs;
    const TYPE_CASH       = 1;
    const TYPE_HUTANG     = 2;
    protected $table      = 'return_rent_cars';
    protected $primaryKey = 'returnRentCId';
    protected $fillable   = [
        'rentCar_id', 'return_date', 'return_name', 'return_address',
        'return_phone', 'notes', 'late_days', 'fine', 'type'
    ];

    public function rentCar(){return $this->belongsTo(RentCar::class, 'rentCar_id','rentCarId');}
    public function debt()
    {
        return $this->morphOne(Debt::class, 'debtable');
    }
    public function paymentAmount()
    {
        return $this->morphMany(PaymentAmount::class, 'payable');
    }
}
