<?php

namespace App\Models\Resources\Vehicle;

use App\{
    Traits\ActivityLogs
};

use Illuminate\{
    Database\Eloquent\Model
};

class VehicleDepreciat extends Model
{
    use ActivityLogs;

    protected $table      = 'vehicle_depreciats'; 
    protected $primaryKey = 'vehicleDepId'; 
    protected $fillable   = [
        'vehicle_id', 'year', 'depreciation_amount', 'book_value',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicleId');
    }
}
