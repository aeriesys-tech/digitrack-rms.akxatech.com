<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_attribute_values', function (Blueprint $table) {
            $table->id('service_attribute_value_id');
            $table->foreignId('service_id');
            $table->foreignId('service_attribute_id');
            $table->string('field_value');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('service_id')->references('service_id')->on('services');
            $table->foreign('service_attribute_id')->references('service_attribute_id')->on('service_attributes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_attribute_values');
    }
};
