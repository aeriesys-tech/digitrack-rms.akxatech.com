<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_variables', function (Blueprint $table) {
            $table->dropForeign(['asset_zone_id']);
            $table->dropColumn('asset_zone_id');
        });
    }

    public function down(): void
    {
        Schema::table('asset_variables', function (Blueprint $table) {
            $table->unsignedBigInteger('asset_zone_id')->nullable();
            $table->foreign('asset_zone_id')->references('asset_zone_id')->on('asset_zones');
        });
    }
};
