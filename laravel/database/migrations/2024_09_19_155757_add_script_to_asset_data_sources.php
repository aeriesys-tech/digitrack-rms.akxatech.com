<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_data_sources', function (Blueprint $table) {
            $table->string('script')->after('data_source_id');
        });
    }

    public function down(): void
    {
        Schema::table('asset_data_sources', function (Blueprint $table) {
            $table->dropColumn('script');
        });
    }
};
