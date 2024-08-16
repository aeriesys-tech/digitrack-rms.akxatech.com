<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_checks', function (Blueprint $table) {
            $table->id('asset_check_id');
            $table->foreignId('check_id');
            $table->foreignId('asset_id');
            $table->foreignId('plant_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('check_id')->references('check_id')->on('checks');
            $table->foreign('asset_id')->references('asset_id')->on('assets');
            $table->foreign('plant_id')->references('plant_id')->on('plants');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_checks');
    }
};
