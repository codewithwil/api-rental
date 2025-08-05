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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable(true);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('last_login_ip', 45)->nullable(); 
            $table->string('last_login_device', 255)->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('branch_id')
            ->references('branchId')
            ->on('branches')
            ->onUpdate("cascade")
            ->onDelete("restrict");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
