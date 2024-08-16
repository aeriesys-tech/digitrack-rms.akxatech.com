<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_services', function (Blueprint $table) {
            $table->id('asset_service_id');
            $table->foreignId('service_id');
            $table->foreignId('asset_id');
            $table->foreignId('plant_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('service_id')->references('service_id')->on('services');
            $table->foreign('asset_id')->references('asset_id')->on('assets');
            $table->foreign('plant_id')->references('plant_id')->on('plants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_services');
    }
};
