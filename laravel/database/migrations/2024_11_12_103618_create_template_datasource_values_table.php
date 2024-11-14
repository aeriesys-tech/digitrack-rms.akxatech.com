<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_datasource_values', function (Blueprint $table) {
            $table->id('template_datasource_value_id');
            $table->foreignId('asset_template_id');
            $table->foreignId('asset_template_datasource_id');
            $table->foreignId('template_zone_id')->nullable();
            $table->foreignId('data_source_id');
            $table->foreignId('data_source_attribute_id');
            $table->string('field_value')->nullable();
            $table->timestamps();
            $table->foreign('data_source_attribute_id')->references('data_source_attribute_id')->on('data_source_attributes');
            $table->foreign('asset_template_datasource_id')->references('asset_template_datasource_id')->on('asset_template_datasources');
            $table->foreign('asset_template_id')->references('asset_template_id')->on('asset_templates');
            $table->foreign('template_zone_id')->references('template_zone_id')->on('template_zones');
            $table->foreign('data_source_id')->references('data_source_id')->on('data_sources');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_datasource_values');
    }
};
