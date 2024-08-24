<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_source_attribute_values', function (Blueprint $table) {
            $table->id('data_source_attribute_value_id');
            $table->foreignId('data_source_id');
            $table->foreignId('data_source_attribute_id');
            $table->string('field_value');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('data_source_id')->references('data_source_id')->on('data_sources');
            $table->foreign('data_source_attribute_id')->references('data_source_attribute_id')->on('data_source_attributes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_source_attribute_values');
    }
};
