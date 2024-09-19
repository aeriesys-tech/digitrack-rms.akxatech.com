<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_variables', function (Blueprint $table) {
            $table->id('user_variable_id');
            $table->foreignId('plant_id');
            $table->foreignId('user_id');
            $table->foreignId('asset_id');
            $table->string('job_no');
            $table->timestamp('job_date');
            $table->string('note')->nullable();
            $table->foreignId('asset_zone_id')->nullable();
            $table->timestamps();
            $table->foreign('asset_zone_id')->references('asset_zone_id')->on('asset_zones');
            $table->foreign('plant_id')->references('plant_id')->on('plants');
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('asset_id')->references('asset_id')->on('assets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_variables');
    }
};
