<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variable_attribute_values', function (Blueprint $table) {
            $table->id('variable_attribute_value_id');
            $table->foreignId('variable_id');
            $table->foreignId('variable_attribute_id');
            $table->string('field_value');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('variable_id')->references('variable_id')->on('variables');
            $table->foreign('variable_attribute_id')->references('variable_attribute_id')->on('variable_attributes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variable_attribute_values');
    }
};
