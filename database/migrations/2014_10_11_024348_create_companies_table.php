<?php

use Illuminate\{
    Database\Migrations\Migration,
    Database\Schema\Blueprint,
    Support\Facades\Schema,
};

return new class extends Migration
{
    public function up(): void
    {
        if(!Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('companyId');   
                $table->string('name', 50);   
                $table->string('web', 75);   
                $table->string('phone', 16);   
                $table->text('address');   
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
