<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_spare_values', function (Blueprint $table) {
            $table->id('template_spare_value_id');
            $table->foreignId('spare_id');
            $table->foreignId('asset_template_id');
            $table->foreignId('asset_template_spare_id');
            $table->foreignId('template_zone_id');
            $table->foreignId('spare_attribute_id');
            $table->string('field_value', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('spare_id')->references('spare_id')->on('spares');
            $table->foreign('asset_template_id')->references('asset_template_id')->on('asset_templates');
            $table->foreign('asset_template_spare_id')->references('asset_template_spare_id')->on('asset_template_spares');
            $table->foreign('template_zone_id')->references('template_zone_id')->on('template_zones');
            $table->foreign('spare_attribute_id')->references('spare_attribute_id')->on('spare_attributes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_spare_values');
    }
};
