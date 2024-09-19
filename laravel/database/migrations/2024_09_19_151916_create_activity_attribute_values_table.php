<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_attribute_values', function (Blueprint $table) {
            $table->id('activity_attribute_value_id');
            $table->foreignId('user_activity_id');
            $table->foreignId('activity_attribute_id');
            $table->string('field_value');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_activity_id')->references('user_activity_id')->on('user_activities');
            $table->foreign('activity_attribute_id')->references('activity_attribute_id')->on('activity_attributes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_attribute_values');
    }
};
