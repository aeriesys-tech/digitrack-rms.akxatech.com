<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_attribute_values', function (Blueprint $table) {
            $table->id('asset_attribute_value_id');
            $table->foreignId('asset_id');
            $table->foreignId('asset_attribute_id');
            $table->string('field_value');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('asset_id')->references('asset_id')->on('assets');
            $table->foreign('asset_attribute_id')->references('asset_attribute_id')->on('asset_attributes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_attribute_values');
    }
};
