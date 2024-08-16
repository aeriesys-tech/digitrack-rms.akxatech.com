<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id('asset_id');
            $table->foreignId('plant_id');
            $table->string('asset_code',100);
            $table->string('asset_name',100);
            $table->foreignId('asset_type_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('plant_id')->references('plant_id')->on('plants');
            $table->foreign('asset_type_id')->references('asset_type_id')->on('asset_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
