<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_template_accessories', function (Blueprint $table) {
            $table->id('asset_template_accessory_id');
            $table->foreignId('area_id');
            $table->foreignId('plant_id');
            $table->foreignId('asset_template_id');
            $table->foreignId('template_zone_id')->nullable();
            $table->foreignId('accessory_type_id');
            $table->string('accessory_name',100);
            $table->string('attachment',100);
            $table->timestamps();
            $table->foreign('area_id')->references('area_id')->on('areas');
            $table->foreign('plant_id')->references('plant_id')->on('plants');
            $table->foreign('asset_template_id')->references('asset_template_id')->on('asset_templates');
            $table->foreign('template_zone_id')->references('template_zone_id')->on('template_zones');
            $table->foreign('accessory_type_id')->references('accessory_type_id')->on('accessory_types');
            $table->index('area_id');
            $table->index('plant_id');
            $table->index('asset_template_id');
            $table->index('template_zone_id');
            $table->index('accessory_type_id');
            $table->index('accessory_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_template_accessories');
    }
};
