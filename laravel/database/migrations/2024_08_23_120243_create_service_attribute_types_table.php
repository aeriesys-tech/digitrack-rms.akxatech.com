<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_attribute_types', function (Blueprint $table) {
            $table->id('service_attribute_type_id');
            $table->foreignId('service_attribute_id');
            $table->foreign('service_attribute_id')->references('service_attribute_id')->on('service_attributes');
            $table->foreignId('service_type_id');
            $table->foreign('service_type_id')->references('service_type_id')->on('service_type');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_attribute_types');
    }
};
