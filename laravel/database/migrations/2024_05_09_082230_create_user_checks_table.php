<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_checks', function (Blueprint $table) {
            $table->id('user_check_id');
            $table->foreignId('plant_id');
            $table->foreignId('user_id');
            $table->foreignId('asset_id');
            $table->string('reference_no');
            $table->timestamp('reference_date');
            $table->string('note')->nullable();
            $table->timestamps();
            $table->foreign('plant_id')->references('plant_id')->on('plants');
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('asset_id')->references('asset_id')->on('assets');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_checks');
    }
};
