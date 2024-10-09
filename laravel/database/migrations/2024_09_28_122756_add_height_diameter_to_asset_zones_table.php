<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_zones', function (Blueprint $table) {
            $table->decimal('height')->nullable()->after('zone_name');
            $table->decimal('diameter')->nullable()->after('height');
        });
    }

    public function down(): void
    {
        Schema::table('asset_zones', function (Blueprint $table) {
            $table->dropColumn('height');
            $table->dropColumn('diameter');
        });
    }
};
