<?php

namespace App\Models\People\Employee;

use App\{
    Models\User,
    Traits\ActivityLogs,
    Models\Files\Files
};


use Illuminate\{
    Database\Eloquent\Model
};

class Employee extends Model
{
    use ActivityLogs;
    const JENKEL_LAKILAKI  = 0;
    const JENKEL_PEREMPUAN = 1;
    protected $table       = 'employees';
    protected $primaryKey  = 'employeeId';
    protected $fillable    = [
        'user_id', 'name', 'phone', 'address', 'gender',
        'birthdate', 'hire_date', 'salary', 'status'
    ];

    public function user(){return $this->belongsTo(User::class, 'user_id', 'id');}
    public function file(){return $this->morphOne(Files::class, 'fileable');}
    public function getGenderLabelAttribute()
    {
        $labels = [
            self::JENKEL_LAKILAKI  => 'Laki-laki',
            self::JENKEL_PEREMPUAN => 'Perempuan',
        ];
    
        return $labels[$this->gender] ?? 'Tidak Diketahui';
    }
}
