<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('break_down_attribute_types', function (Blueprint $table) {
            $table->id('break_down_attribute_type_id');
            $table->foreignId('break_down_attribute_id');
            $table->foreign('break_down_attribute_id')->references('break_down_attribute_id')->on('break_down_attributes');
            $table->foreignId('break_down_type_id');
            $table->foreign('break_down_type_id')->references('break_down_type_id')->on('break_down_types');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('break_down_attribute_types');
    }
};
