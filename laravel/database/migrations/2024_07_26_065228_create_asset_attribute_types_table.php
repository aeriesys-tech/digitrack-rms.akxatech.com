<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_attribute_types', function (Blueprint $table) {
            $table->id('asset_attribute_type_id');
            $table->foreignId('asset_attribute_id');
            $table->foreign('asset_attribute_id')->references('asset_attribute_id')->on('asset_attributes');
            $table->foreignId('asset_type_id');
            $table->foreign('asset_type_id')->references('asset_type_id')->on('asset_type');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_attribute_types');
    }
};
