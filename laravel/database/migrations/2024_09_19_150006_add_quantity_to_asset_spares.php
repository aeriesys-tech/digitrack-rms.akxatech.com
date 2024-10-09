<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_spares', function (Blueprint $table) {
            $table->integer('quantity')->nullable()->after('plant_id');
        });
    }

    public function down(): void
    {
        Schema::table('asset_spares', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};
