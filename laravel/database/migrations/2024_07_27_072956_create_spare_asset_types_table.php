<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spare_asset_types', function (Blueprint $table) {
            $table->id('spare_asset_type_id');
            $table->foreignId('spare_id');
            $table->foreignId('asset_type_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('asset_type_id')->references('asset_type_id')->on('asset_type');
            $table->foreign('spare_id')->references('spare_id')->on('spares');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spare_asset_types');
    }
};
