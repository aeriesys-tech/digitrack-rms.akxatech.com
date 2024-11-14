<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->unsignedBigInteger('asset_template_id')->nullable()->after('diameter');
            $table->foreign('asset_template_id')->references('asset_template_id')->on('asset_templates');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['asset_template_id']);
            $table->dropColumn('asset_template_id');
        });
    }
};
