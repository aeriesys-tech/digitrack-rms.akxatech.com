<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('break_down_attribute_values', function (Blueprint $table) {
            $table->id('break_down_attribute_value_id');
            $table->foreignId('break_down_list_id');
            $table->foreignId('break_down_attribute_id');
            $table->string('field_value');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('break_down_list_id')->references('break_down_list_id')->on('break_down_lists');
            $table->foreign('break_down_attribute_id')->references('break_down_attribute_id')->on('break_down_attributes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('break_down_attribute_values');
    }
};
