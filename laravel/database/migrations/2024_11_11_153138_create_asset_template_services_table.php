<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_template_services', function (Blueprint $table) {
            $table->id('asset_template_service_id');
            $table->foreignId('area_id')->nullable();
            $table->foreignId('service_id');
            $table->foreignId('asset_template_id');
            $table->foreignId('template_zone_id')->nullable();
            $table->foreignId('plant_id');
            $table->foreignId('service_type_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('area_id')->references('area_id')->on('areas');
            $table->foreign('service_id')->references('service_id')->on('services');
            $table->foreign('asset_template_id')->references('asset_template_id')->on('asset_templates');
            $table->foreign('template_zone_id')->references('template_zone_id')->on('template_zones');
            $table->foreign('plant_id')->references('plant_id')->on('plants');
            $table->foreign('service_type_id')->references('service_type_id')->on('service_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_template_services');
    }
};
