<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_services', function (Blueprint $table) {
            $table->foreignId('asset_zone_id')->nullable()->after('asset_id');
            $table->foreign('asset_zone_id')->references('asset_zone_id')->on('asset_zones');
        });
    }

    public function down(): void
    {
        Schema::table('user_services', function (Blueprint $table) {
            $table->dropForeign(['asset_zone_id']);
            $table->dropColumn('asset_zone_id');
        });
    }
};
