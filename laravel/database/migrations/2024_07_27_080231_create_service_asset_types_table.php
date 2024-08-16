<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_asset_types', function (Blueprint $table) {
            $table->id('service_asset_type_id');
            $table->foreignId('service_id');
            $table->foreignId('asset_type_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('asset_type_id')->references('asset_type_id')->on('asset_type');
            $table->foreign('service_id')->references('service_id')->on('services');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_asset_types');
    }
};
