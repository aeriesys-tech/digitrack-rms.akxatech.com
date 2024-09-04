<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_checks', function (Blueprint $table) {
            $table->foreignId('area_id')->after('asset_check_id');
            $table->foreign('area_id')->references('area_id')->on('areas');
            $table->foreignId('asset_zone_id')->nullable()->after('asset_id');
            $table->foreign('asset_zone_id')->references('asset_zone_id')->on('asset_zones');
        });
    }

    public function down(): void
    {
        Schema::table('asset_checks', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
            $table->dropColumn('area_id');
            $table->dropForeign(['asset_zone_id']);
            $table->dropColumn('asset_zone_id');
        });
    }
};
