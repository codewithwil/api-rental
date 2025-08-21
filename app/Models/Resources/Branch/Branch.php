<?php

namespace App\Models\Resources\Branch;

use App\{
    Models\Resources\Company\Company,
    Models\User,
    Traits\ActivityLogs,
    Models\Resources\Vehicle\Vehicle
};

use Illuminate\{
    Database\Eloquent\Model
};

class Branch extends Model
{
    use ActivityLogs;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    protected $table      = 'branches';
    protected $primaryKey = 'branchId';
    protected $fillable   = [
        'company_id', 'branchName','address', 'email', 'operationalHours', 
        'phone', 'ltd', 'lng', 'status' 
    ];

    public function users(){return $this->hasMany(User::class, 'branch_id', 'branchId');}
    public function company(){return $this->belongsTo(Company::class, 'company_id', 'companyId');}
    public function vehicle(){return $this->hasMany(Vehicle::class, 'branch_id', 'branchId');}
}
