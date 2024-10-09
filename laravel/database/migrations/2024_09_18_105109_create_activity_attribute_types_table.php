<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_attribute_types', function (Blueprint $table) {
            $table->id('activity_attribute_type_id');
            $table->foreignId('activity_attribute_id');
            $table->foreign('activity_attribute_id')->references('activity_attribute_id')->on('activity_attributes');
            $table->foreignId('reason_id');
            $table->foreign('reason_id')->references('reason_id')->on('reasons');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_attribute_types');
    }
};
