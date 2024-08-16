<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id('equipment_id');
            $table->foreignId('plant_id');
            $table->foreignId('equipment_type_id');
            $table->string('equipment_code');
            $table->string('equipment_name');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('plant_id')->references('plant_id')->on('plants');
            $table->foreign('equipment_type_id')->references('equipment_type_id')->on('equipment_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
