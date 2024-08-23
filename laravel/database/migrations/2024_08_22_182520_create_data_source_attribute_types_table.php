<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_source_attribute_types', function (Blueprint $table) {
            $table->id('data_source_attribute_type_id');
            $table->foreignId('data_source_attribute_id');
            $table->foreign('data_source_attribute_id')->references('data_source_attribute_id')->on('data_source_attributes');
            $table->foreignId('data_source_type_id');
            $table->foreign('data_source_type_id')->references('data_source_type_id')->on('data_source_types');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_source_attribute_types');
    }
};
