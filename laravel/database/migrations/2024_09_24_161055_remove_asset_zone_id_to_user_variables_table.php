<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_variables', function (Blueprint $table) {
            $table->dropForeign(['asset_zone_id']);
            $table->dropColumn('asset_zone_id');
            $table->dropForeign(['variable_id']);
            $table->dropColumn('variable_id');
            $table->dropColumn('value');
        });
    }

    public function down(): void
    {
        Schema::table('user_variables', function (Blueprint $table) {
            $table->unsignedBigInteger('asset_zone_id')->nullable();
            $table->foreign('asset_zone_id')->references('asset_zone_id')->on('asset_zones');
            $table->unsignedBigInteger('variable_id')->nullable();
            $table->foreign('variable_id')->references('variable_id')->on('variables');
            $table->string('value')->nullable();
        });
    }
};
