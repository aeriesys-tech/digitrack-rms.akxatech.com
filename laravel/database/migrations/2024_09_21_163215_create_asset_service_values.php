<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_service_values', function (Blueprint $table) {
            $table->id('asset_service_value_id');
            $table->foreignId('asset_service_id');
            $table->foreignId('asset_id');
            $table->foreignId('service_id');
            $table->foreignId('asset_zone_id');
            $table->foreignId('service_attribute_id');
            $table->string('field_value')->nullable();
            $table->timestamps();
            $table->foreign('asset_service_id')->references('asset_service_id')->on('asset_services');
            $table->foreign('service_id')->references('service_id')->on('services');
            $table->foreign('service_attribute_id')->references('service_attribute_id')->on('service_attributes');
            $table->foreign('asset_id')->references('asset_id')->on('assets');
            $table->foreign('asset_zone_id')->references('asset_zone_id')->on('asset_zones');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_service_values');
    }
};
