<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_variable_values', function (Blueprint $table) {
            $table->id('template_variable_value_id');
            $table->foreignId('asset_template_variable_id');
            $table->foreignId('asset_template_id');
            $table->foreignId('variable_id');
            $table->foreignId('template_zone_id');
            $table->foreignId('variable_attribute_id');
            $table->string('field_value')->nullable();
            $table->timestamps();
            $table->foreign('asset_template_variable_id')->references('asset_template_variable_id')->on('asset_template_variables');
            $table->foreign('variable_id')->references('variable_id')->on('variables');
            $table->foreign('variable_attribute_id')->references('variable_attribute_id')->on('variable_attributes');
            $table->foreign('asset_template_id')->references('asset_template_id')->on('asset_templates');
            $table->foreign('template_zone_id')->references('template_zone_id')->on('template_zones');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_variable_values');
    }
};
