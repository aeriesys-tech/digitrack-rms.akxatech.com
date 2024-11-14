<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_template_datasources', function (Blueprint $table) {
            $table->id('asset_template_datasource_id');
            $table->foreignId('area_id');
            $table->foreignId('plant_id');
            $table->foreignId('asset_template_id');
            $table->foreignId('template_zone_id')->nullable();
            $table->foreignId('data_source_type_id');
            $table->foreignId('data_source_id');
            $table->string('script');
            $table->timestamps();
            $table->foreign('area_id')->references('area_id')->on('areas');
            $table->foreign('plant_id')->references('plant_id')->on('plants');
            $table->foreign('data_source_type_id')->references('data_source_type_id')->on('data_source_types');
            $table->foreign('asset_template_id')->references('asset_template_id')->on('asset_templates');
            $table->foreign('template_zone_id')->references('template_zone_id')->on('template_zones');
            $table->foreign('data_source_id')->references('data_source_id')->on('data_sources');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_template_datasources');
    }
};
