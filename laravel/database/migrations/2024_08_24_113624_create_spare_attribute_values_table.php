<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spare_attribute_values', function (Blueprint $table) {
            $table->id('spare_attribute_value_id');
            $table->foreignId('spare_id');
            $table->foreignId('spare_attribute_id');
            $table->string('field_value');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('spare_id')->references('spare_id')->on('spares');
            $table->foreign('spare_attribute_id')->references('spare_attribute_id')->on('spare_attributes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spare_attribute_values');
    }
};
