<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_spare_values', function (Blueprint $table) {
            $table->id('asset_spare_value_id');
            $table->foreignId('asset_spare_id');
            $table->foreignId('asset_id');
            $table->foreignId('spare_id');
            $table->foreignId('asset_zone_id');
            $table->foreignId('spare_attribute_id');
            $table->string('field_value')->nullable();
            $table->timestamps();
            $table->foreign('asset_spare_id')->references('asset_spare_id')->on('asset_spares');
            $table->foreign('spare_id')->references('spare_id')->on('spares');
            $table->foreign('spare_attribute_id')->references('spare_attribute_id')->on('spare_attributes');
            $table->foreign('asset_id')->references('asset_id')->on('assets');
            $table->foreign('asset_zone_id')->references('asset_zone_id')->on('asset_zones');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_spare_values');
    }
};
