<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variable_attribute_types', function (Blueprint $table) {
            $table->id('variable_attribute_type_id');
            $table->foreignId('variable_attribute_id');
            $table->foreign('variable_attribute_id')->references('variable_attribute_id')->on('variable_attributes');
            $table->foreignId('variable_type_id');
            $table->foreign('variable_type_id')->references('variable_type_id')->on('variable_types');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variable_attribute_types');
    }
};
