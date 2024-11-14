<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_template_spares', function (Blueprint $table) {
            $table->id('asset_template_spare_id');
            $table->foreignId('spare_id');
            $table->foreignId('asset_template_id');
            $table->foreignId('plant_id');
            $table->foreignId('area_id');
            $table->foreignId('template_zone_id');
            $table->foreignId('spare_type_id');
            $table->integer('quantity');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('spare_id')->references('spare_id')->on('spares');
            $table->foreign('asset_template_id')->references('asset_template_id')->on('asset_templates');
            $table->foreign('plant_id')->references('plant_id')->on('plants');
            $table->foreign('area_id')->references('area_id')->on('areas');
            $table->foreign('template_zone_id')->references('template_zone_id')->on('template_zones');
            $table->foreign('spare_type_id')->references('spare_type_id')->on('spare_types');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_template_spares');
    }
};
