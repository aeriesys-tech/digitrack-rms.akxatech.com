<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_variables', function (Blueprint $table) {
            $table->id('asset_variable_id');
            $table->foreignId('area_id');
            $table->foreignId('plant_id');
            $table->foreignId('asset_id');
            $table->foreignId('asset_zone_id')->nullable();
            $table->foreignId('variable_type_id');
            $table->foreignId('variable_id');
            $table->timestamps();
            $table->foreign('area_id')->references('area_id')->on('areas');
            $table->foreign('plant_id')->references('plant_id')->on('plants');
            $table->foreign('asset_id')->references('asset_id')->on('assets');
            $table->foreign('asset_zone_id')->references('asset_zone_id')->on('asset_zones');
            $table->foreign('variable_type_id')->references('variable_type_id')->on('variable_types');
            $table->foreign('variable_id')->references('variable_id')->on('variables');
            $table->index('area_id');
            $table->index('plant_id');
            $table->index('asset_id');
            $table->index('asset_zone_id');
            $table->index('variable_type_id');
            $table->index('variable_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_variables');
    }
};
