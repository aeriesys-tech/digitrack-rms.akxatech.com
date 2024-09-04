<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_services', function (Blueprint $table) {
            $table->foreignId('area_id')->nullable()->after('asset_id');
            $table->foreign('area_id')->references('area_id')->on('areas');
            $table->foreignId('asset_zone_id')->nullable()->after('area_id');
            $table->foreign('asset_zone_id')->references('asset_zone_id')->on('asset_zones');
            $table->foreignId('service_type_id')->nullable()->after('asset_zone_id');
            $table->foreign('service_type_id')->references('service_type_id')->on('service_type');
        });
    }

    public function down(): void
    {
        Schema::table('asset_services', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
            $table->dropColumn('area_id');
            $table->dropForeign(['asset_zone_id']);
            $table->dropColumn('asset_zone_id');
            $table->dropForeign(['service_type_id']);
            $table->dropColumn('service_type_id');
        });
    }
};
