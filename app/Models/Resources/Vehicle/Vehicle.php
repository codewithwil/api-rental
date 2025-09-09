<?php

namespace App\Models\Resources\Vehicle;

use App\{
    Models\Resources\Branch\Branch,
    Models\Resources\Brand\Brand,
    Models\Resources\Category\Category,
    Models\User,
    Traits\ActivityLogs,
    Models\Files\Files,
    Models\Report\WeeklyReport\WeeklyReport
};

use Illuminate\{
    Database\Eloquent\Model
};

class Vehicle extends Model
{
    use ActivityLogs;
    const STATUS_DELETED  = 0;
    const STATUS_INACTIVE = 1;
    const STATUS_ACTIVE   = 2;
    const STATUS_REPAIR   = 3;
    const STATUS_RENT     = 4;
    protected $table      = 'vehicles';
    protected $primaryKey = 'vehicleId';
    protected $fillable   = [
        'user_id','branch_id', 'category_id', 'brand_id', 'name', 
        'plate_number', 'color', 'year', 'acquisition_cost',
        'kir_expiry_date', 'stnk_date', 'bpkb_date', 'note', 'status'
    ];

    protected static function boot(){parent::boot();}
    public function user(){return $this->belongsTo(User::class, 'user_id', 'id');}
    public function branch(){return $this->belongsTo(Branch::class, 'branch_id', 'branchId');}
    public function category(){return $this->belongsTo(Category::class, 'category_id', 'categoryId');}
    public function Brand(){return $this->belongsTo(Brand::class, 'brand_id', 'brandId');}
    public function vehicleDepreciat(){return $this->belongsTo(VehicleDepreciat::class, 'vehicle_id', 'vehicleId');}
    public function file(){return $this->morphOne(Files::class, 'fileable');}
    public function weeklyReport(){return $this->hasMany(WeeklyReport::class, 'vehcile_id', 'vehicleId');}
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_DELETED  => 'Dihapus',
            self::STATUS_INACTIVE => 'Tidak aktif',
            self::STATUS_ACTIVE   => 'Aktif',
            self::STATUS_REPAIR   => 'Perbaikan',
            self::STATUS_RENT     => 'Disewa',
        ];
    
        return $labels[$this->status] ?? 'Tidak Diketahui';
    }
}
