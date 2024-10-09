<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_variable_values', function (Blueprint $table) {
            $table->id('asset_variable_value_id');
            $table->foreignId('asset_variable_id');
            $table->foreignId('asset_id');
            $table->foreignId('variable_id');
            $table->foreignId('asset_zone_id');
            $table->foreignId('variable_attribute_id');
            $table->string('field_value')->nullable();
            $table->timestamps();
            $table->foreign('asset_variable_id')->references('asset_variable_id')->on('asset_variables');
            $table->foreign('variable_id')->references('variable_id')->on('variables');
            $table->foreign('variable_attribute_id')->references('variable_attribute_id')->on('variable_attributes');
            $table->foreign('asset_id')->references('asset_id')->on('assets');
            $table->foreign('asset_zone_id')->references('asset_zone_id')->on('asset_zones');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_variable_values');
    }
};
