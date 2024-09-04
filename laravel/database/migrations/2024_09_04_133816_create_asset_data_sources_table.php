<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_data_sources', function (Blueprint $table) {
            $table->id('asset_data_source_id');
            $table->foreignId('area_id');
            $table->foreignId('plant_id');
            $table->foreignId('asset_id');
            $table->foreignId('asset_zone_id')->nullable();
            $table->foreignId('data_source_type_id');
            $table->foreignId('data_source_id');
            $table->timestamps();
            $table->foreign('area_id')->references('area_id')->on('areas');
            $table->foreign('plant_id')->references('plant_id')->on('plants');
            $table->foreign('asset_id')->references('asset_id')->on('assets');
            $table->foreign('asset_zone_id')->references('asset_zone_id')->on('asset_zones');
            $table->foreign('data_source_type_id')->references('data_source_type_id')->on('data_source_types');
            $table->foreign('data_source_id')->references('data_source_id')->on('data_sources');
            $table->index('area_id');
            $table->index('plant_id');
            $table->index('asset_id');
            $table->index('asset_zone_id');
            $table->index('data_source_type_id');
            $table->index('data_source_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_data_sources');
    }
};
