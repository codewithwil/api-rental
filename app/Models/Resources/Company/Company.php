<?php

namespace App\Models\Resources\Company;

use App\{
    Traits\ActivityLogs,
    Models\Resources\Branch\Branch,
    Models\Files\Files
};

use Illuminate\{
    Database\Eloquent\Model
};

class Company extends Model
{
    use ActivityLogs;
    protected $table           = 'companies';
    protected $primaryKey      = 'companyId';
    protected $fillable        = [
        'companyId','name', 'web', 'phone', 'address'
    ];

    public function branch(){return $this->hasOne(Branch::class, 'company_id', 'companyId');}
    public function file(){return $this->morphOne(Files::class, 'fileable');}
}
