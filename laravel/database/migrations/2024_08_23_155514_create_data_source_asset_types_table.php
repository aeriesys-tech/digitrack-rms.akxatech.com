<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_source_asset_types', function (Blueprint $table) {
            $table->id('data_source_asset_type_id');
            $table->foreignId('data_source_id');
            $table->foreignId('asset_type_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('asset_type_id')->references('asset_type_id')->on('asset_type');
            $table->foreign('data_source_id')->references('data_source_id')->on('data_sources');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_source_asset_types');
    }
};
