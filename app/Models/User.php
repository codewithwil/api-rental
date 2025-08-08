<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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

    // public function admin(){return $this->hasOne(Admin::class, 'user_id');}
    // public function supervisor(){return $this->hasOne(Supervisor::class, 'user_id');}
    // public function employee(){return $this->hasOne(Employee::class, 'user_id');}
    // public function member(){return $this->hasOne(Member::class, 'user_id');}
}
