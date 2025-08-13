<?php

namespace App\Models\Resources\Category;

use App\{
    Traits\ActivityLogs
};

use Illuminate\{
    Database\Eloquent\Model
};

class Category extends Model
{
    use ActivityLogs;
    const TYPE_CAR        = 1;
    const TYPE_MOTORCYCLE = 2;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    protected $table      = 'categories';
    protected $primaryKey = 'categoryId';
    protected $fillable   = [
        'name', 'type','status'
    ];
}
