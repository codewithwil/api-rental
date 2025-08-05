<?php

namespace App\Models\People\Supervisor;

use App\{
    Models\User,
    Traits\ActivityLogs
};

use Illuminate\{
    Database\Eloquent\Model
};

class Supervisor extends Model
{
    use ActivityLogs;
    protected $table      = 'supervisors';
    protected $primaryKey = 'supervisorId';
    protected $fillable   = [
        'user_id', 'name', 'phone',
    ];

    public function user(){return $this->belongsTo(User::class, 'user_id', 'id');}
}
