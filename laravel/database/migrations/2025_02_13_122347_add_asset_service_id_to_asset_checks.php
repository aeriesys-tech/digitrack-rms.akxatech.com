<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_checks', function (Blueprint $table) {
            $table->unsignedBigInteger('asset_service_id')->nullable()->after('asset_zone_id');
            $table->foreign('asset_service_id')->references('asset_service_id')->on('asset_services');
        });
    }

    public function down(): void
    {
        Schema::table('asset_checks', function (Blueprint $table) {
            $table->dropForeign(['asset_service_id']);
            $table->dropColumn('asset_service_id');
        });
    }
};
