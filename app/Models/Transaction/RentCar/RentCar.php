<?php

namespace App\Models\Transaction\RentCar;

use App\{
    Models\Resources\Vehicle\Vehicle,
    Traits\ActivityLogs,
    Models\Transactions\Payment\PaymentAmount\PaymentAmount
};

use Illuminate\{
    Database\Eloquent\Model
};

class RentCar extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    use ActivityLogs;
    protected $table      = 'rent_cars';
    protected $primaryKey = 'rentCarId';
    protected $fillable   = [
        'vehicle_id','renter_name','renter_address',
        'renter_phone','startDate','endDate',
        'pricePerDay','notes','status',
    ];

    public function vehicle(){return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicleId');}
    public function paymentAmount()
    {
        return $this->morphMany(PaymentAmount::class, 'payable');
    }
}
