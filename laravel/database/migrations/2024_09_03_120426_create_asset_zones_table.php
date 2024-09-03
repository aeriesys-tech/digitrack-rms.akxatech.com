<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_zones', function (Blueprint $table) {
            $table->id('asset_zone_id');
            $table->foreignId('asset_id');
            $table->string('zone_name',100);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('asset_id')->references('asset_id')->on('assets');
            $table->index('asset_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_zones');
    }
};
