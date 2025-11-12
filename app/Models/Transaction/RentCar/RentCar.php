<?php

namespace App\Models\Transaction\RentCar;

use App\{
    Models\Resources\Vehicle\Vehicle,
    Traits\ActivityLogs,
    Models\Transactions\Payment\PaymentAmount\PaymentAmount,
    Models\Transactions\ReturnRentCar\ReturnRentCar
};
use App\Models\Transactions\Debt\Debt;
use Illuminate\{
    Database\Eloquent\Model
};

class RentCar extends Model
{
    const TYPE_CASH       = 1;
    const TYPE_HUTANG     = 2;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    use ActivityLogs;
    protected $table      = 'rent_cars';
    protected $primaryKey = 'rentCarId';
    protected $fillable   = [
        'vehicle_id','owner','renter_name','renter_address',
        'renter_phone','startDate','endDate',
        'pricePerDay','penalty','notes','type','status',
    ];

    public function vehicle(){return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicleId');}
    public function paymentAmount(){return $this->morphMany(PaymentAmount::class, 'payable');}
    public function debts(){return $this->morphMany(Debt::class, 'debtable');}
    public function returnRentCar(){return $this->hasMany(ReturnRentCar::class, 'rentCar_id', 'rentCarId');}
}
