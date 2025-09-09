<?php

use Illuminate\{
    Database\Migrations\Migration,
    Database\Schema\Blueprint,
    Support\Facades\Schema
};

return new class extends Migration
{
    public function up(): void
    {
        if(!Schema::hasTable('weekly_report_details')) {
            Schema::create('weekly_report_details', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('weekReportDetId');
                $table->unsignedBigInteger('weekReport_id');
                $table->string('component', 75); 
                $table->string('position', 75);
                $table->timestamps();

                $table->foreign('weekReport_id')
                ->references('weekReportId')
                ->on('weekly_reports')
                ->onUpdate("cascade")
                ->onDelete("restrict");
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_report_details');
    }
};
