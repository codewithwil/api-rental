<?php

namespace App\Models\Report\WeeklyReport;

use App\{
    Models\Files\Files
};

use Illuminate\{
    Database\Eloquent\Model
};

class WeeklyReportDetail extends Model
{
    const TYPE_PHOTO      = 1;
    const TYPE_VIDEO      = 2;
    protected $table      = 'weekly_report_details';
    protected $primaryKey = 'weekReportDetId';
    protected $fillable   = [
        'weekReport_id' ,'component', 'position'
    ];

    public function weeklyReport(){
        return $this->belongsTo(WeeklyReport::class, 'weekReport_id', 'weekReportId');
    }

    public function file(){return $this->morphOne(Files::class, 'fileable');}
}
