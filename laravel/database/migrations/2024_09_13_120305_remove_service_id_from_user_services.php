<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('user_services', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
            $table->dropColumn('service_cost');
            $table->dropForeign(['asset_zone_id']);
            $table->dropColumn('asset_zone_id');
        });
    }

    public function down(): void
    {
        Schema::table('user_services', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id')->nullable();
            $table->foreign('service_id')->references('service_id')->on('services');
            $table->unsignedBigInteger('asset_zone_id')->nullable();
            $table->foreign('asset_zone_id')->references('asset_zone_id')->on('asset_zones');
            $table->string('service_cost')->nullable(); 
        });
    }
};
