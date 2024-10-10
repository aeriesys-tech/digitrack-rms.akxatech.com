<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_type', function (Blueprint $table) {
            $table->string('geometry_type')->nullable()->after('asset_type_name');
        });
    }

    public function down(): void
    {
        Schema::table('asset_type', function (Blueprint $table) {
            $table->dropColumn('geometry_type');
        });
    }
};
