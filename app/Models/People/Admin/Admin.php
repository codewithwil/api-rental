<?php

namespace App\Models\People\Admin;

use App\{
    Models\User,
    Traits\ActivityLogs,
    Models\Files\Files
};


use Illuminate\{
    Database\Eloquent\Model
};

class Admin extends Model
{
    use ActivityLogs;
    protected $table      = 'admins';
    protected $primaryKey = 'adminId';
    protected $fillable   = [
        'user_id', 'name', 'phone',
    ];

    protected static function boot(){parent::boot();}
    public function user(){return $this->belongsTo(User::class, 'user_id', 'id');}
    public function file(){return $this->morphOne(Files::class, 'fileable');}
}
