<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_template_checks', function (Blueprint $table) {
            $table->unsignedBigInteger('asset_template_service_id')->nullable()->after('default_value');
            $table->foreign('asset_template_service_id')->references('asset_template_service_id')->on('asset_template_services');
        });
    }

    public function down(): void
    {
        Schema::table('asset_template_checks', function (Blueprint $table) {
            $table->dropForeign(['asset_template_service_id']);
            $table->dropColumn('asset_template_service_id');
        });
    }
};
