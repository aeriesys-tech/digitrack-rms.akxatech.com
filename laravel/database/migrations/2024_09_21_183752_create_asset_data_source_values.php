<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_data_source_values', function (Blueprint $table) {
            $table->id('asset_data_source_value_id');
            $table->foreignId('asset_data_source_id');
            $table->foreignId('asset_id');
            $table->foreignId('data_source_id');
            $table->foreignId('asset_zone_id');
            $table->foreignId('data_source_attribute_id');
            $table->string('field_value')->nullable();
            $table->timestamps();
            $table->foreign('asset_data_source_id')->references('asset_data_source_id')->on('asset_data_sources');
            $table->foreign('data_source_id')->references('data_source_id')->on('data_sources');
            $table->foreign('data_source_attribute_id')->references('data_source_attribute_id')->on('data_source_attributes');
            $table->foreign('asset_id')->references('asset_id')->on('assets');
            $table->foreign('asset_zone_id')->references('asset_zone_id')->on('asset_zones');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_data_source_values');
    }
};
