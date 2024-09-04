<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_accessories', function (Blueprint $table) {
            $table->id('asset_accessory_id');
            $table->foreignId('area_id');
            $table->foreignId('plant_id');
            $table->foreignId('asset_id');
            $table->foreignId('asset_zone_id')->nullable();
            $table->foreignId('accessory_type_id');
            $table->string('accessory_name',100);
            $table->string('attachment',100);
            $table->timestamps();
            $table->foreign('area_id')->references('area_id')->on('areas');
            $table->foreign('plant_id')->references('plant_id')->on('plants');
            $table->foreign('asset_id')->references('asset_id')->on('assets');
            $table->foreign('asset_zone_id')->references('asset_zone_id')->on('asset_zones');
            $table->foreign('accessory_type_id')->references('accessory_type_id')->on('accessory_types');
            $table->index('area_id');
            $table->index('plant_id');
            $table->index('asset_id');
            $table->index('asset_zone_id');
            $table->index('accessory_type_id');
            $table->index('accessory_name');
            $table->index('attachment');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_accessories');
    }
};
