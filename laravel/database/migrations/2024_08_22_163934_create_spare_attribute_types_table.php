<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spare_attribute_types', function (Blueprint $table) {
            $table->id('spare_attribute_type_id');
            $table->foreignId('spare_attribute_id');
            $table->foreign('spare_attribute_id')->references('spare_attribute_id')->on('spare_attributes');
            $table->foreignId('spare_type_id');
            $table->foreign('spare_type_id')->references('spare_type_id')->on('spare_types');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spare_attribute_types');
    }
};
