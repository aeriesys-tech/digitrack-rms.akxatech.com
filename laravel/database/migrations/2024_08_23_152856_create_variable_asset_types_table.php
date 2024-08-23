<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variable_asset_types', function (Blueprint $table) {
            $table->id('variable_asset_type_id');
            $table->foreignId('variable_id');
            $table->foreignId('asset_type_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('asset_type_id')->references('asset_type_id')->on('asset_type');
            $table->foreign('variable_id')->references('variable_id')->on('variables');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variable_asset_types');
    }
};
