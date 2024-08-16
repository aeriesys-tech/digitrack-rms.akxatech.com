<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('check_asset_types', function (Blueprint $table) {
            $table->id('check_asset_type_id');
            $table->foreignId('check_id');
            $table->foreignId('asset_type_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('asset_type_id')->references('asset_type_id')->on('asset_type');
            $table->foreign('check_id')->references('check_id')->on('checks');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('check_asset_types');
    }
};
