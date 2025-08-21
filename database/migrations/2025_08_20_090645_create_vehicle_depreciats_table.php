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
        if(!Schema::hasTable('vehicle_depreciats')) {
            Schema::create('vehicle_depreciats', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('vehicleDepId');
                $table->unsignedBigInteger('vehicle_id');
                $table->year('year');
                $table->decimal('depreciation_amount', 15, 2); 
                $table->decimal('book_value', 15, 2); 
                $table->timestamps();

                $table->foreign('vehicle_id')
                    ->references('vehicleId')
                    ->on('vehicles')
                    ->cascadeOnDelete();
                });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_depreciats');
    }
};
