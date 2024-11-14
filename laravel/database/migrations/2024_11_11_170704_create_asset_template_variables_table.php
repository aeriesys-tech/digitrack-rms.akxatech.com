<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_template_variables', function (Blueprint $table) {
            $table->id('asset_template_variable_id');
            $table->foreignId('area_id');
            $table->foreignId('plant_id');
            $table->foreignId('asset_template_id');
            $table->foreignId('template_zone_id')->nullable();
            $table->foreignId('variable_type_id');
            $table->foreignId('variable_id');
            $table->timestamps();
            $table->foreign('area_id')->references('area_id')->on('areas');
            $table->foreign('plant_id')->references('plant_id')->on('plants');
            $table->foreign('asset_template_id')->references('asset_template_id')->on('asset_templates');
            $table->foreign('template_zone_id')->references('template_zone_id')->on('template_zones');
            $table->foreign('variable_type_id')->references('variable_type_id')->on('variable_types');
            $table->foreign('variable_id')->references('variable_id')->on('variables');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_template_variables');
    }
};
