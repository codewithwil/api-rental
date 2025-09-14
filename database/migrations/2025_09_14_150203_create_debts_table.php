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
        if(!Schema::hasTable('debts')) {
            Schema::create('debts', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->id('debtId');
                $table->morphs('debtable'); 

                $table->decimal('amount', 12, 2)->default(0);
                $table->date('due_date')->nullable(); 
                $table->tinyInteger('status')->default(0); 

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
