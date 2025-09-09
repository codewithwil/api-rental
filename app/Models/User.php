<?php

namespace App\Models;

use App\{
    Models\People\Admin\Admin,
    Models\People\Employee\Employee,
    Models\People\Supervisor\Supervisor,
    Models\Resources\Vehicle\Vehicle,
    Models\History\Activity\ActivityLog,
    Models\Report\WeeklyReport\WeeklyReport
};

use Illuminate\{
    Database\Eloquent\Factories\HasFactory,
    Foundation\Auth\User as Authenticatable,
    Notifications\Notifiable,
};

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    protected $fillable = [
        'email','password','last_login_ip', 
        'last_login_device', 'last_active_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function admin(){return $this->hasOne(Admin::class, 'user_id');}
    public function supervisor(){return $this->hasOne(Supervisor::class, 'user_id');}
    public function employee(){return $this->hasOne(Employee::class, 'user_id');}
    public function vehicle(){return $this->hasMany(Vehicle::class, 'user_id');}
    public function activityLog(){return $this->hasMany(ActivityLog::class, 'user_id');}
    public function weeklyReport(){return $this->hasMany(WeeklyReport::class, 'user_id');}
}
