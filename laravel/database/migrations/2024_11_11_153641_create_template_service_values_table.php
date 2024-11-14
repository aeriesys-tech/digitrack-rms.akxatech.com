<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_service_values', function (Blueprint $table) {
            $table->id('template_service_value_id');
            $table->foreignId('asset_template_service_id');
            $table->foreignId('asset_template_id');
            $table->foreignId('service_id');
            $table->foreignId('template_zone_id');
            $table->foreignId('service_attribute_id');
            $table->string('field_value')->nullable();
            $table->timestamps();
            $table->foreign('asset_template_service_id')->references('asset_template_service_id')->on('asset_template_services');
            $table->foreign('service_id')->references('service_id')->on('services');
            $table->foreign('asset_template_id')->references('asset_template_id')->on('asset_templates');
            $table->foreign('template_zone_id')->references('template_zone_id')->on('template_zones');
            $table->foreign('service_attribute_id')->references('service_attribute_id')->on('service_attributes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_service_values');
    }
};
