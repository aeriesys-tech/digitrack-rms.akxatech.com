<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('scanner_code',255)->nullable()->after('asset_template_id');
            $table->string('ppms_code',255)->nullable()->after('scanner_code');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('scanner_code');
            $table->dropColumn('ppms_code');
        });
    }
};
