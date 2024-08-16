<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name',100);
            $table->string('email',100);  
            $table->string('password',255);      
            $table->string('mobile_no',15);
            $table->foreignId('role_id');
            $table->foreignId('plant_id');
            $table->text('address')->nullable();
            $table->string('avatar',250)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('plant_id')->references('plant_id')->on('plants');
            $table->foreign('role_id')->references('role_id')->on('roles');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('otp');
            $table->string('token');
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        DB::table('users')->insert([
            'role_id' => 1, 
            'name' => 'Bharatesh Shanawad',
            'email' => 'bharatesh.s@akxatech.com',
            'password' => Hash::make('1qaz2wsx'), 
            'mobile_no' => '9535342875', 
            'address' => null,
            'avatar' => null, 
            'plant_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
