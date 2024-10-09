<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_variables', function (Blueprint $table) {
            $table->unsignedBigInteger('variable_id')->nullable()->after('asset_zone_id');
            $table->foreign('variable_id')->references('variable_id')->on('variables');
            $table->string('value')->nullable()->after('variable_id');
        });
    }

    public function down(): void
    {
        Schema::table('user_variables', function (Blueprint $table) {
            $table->dropForeign(['variable_id']);
            $table->dropColumn('variable_id');
            $table->dropColumn('value');
        });
    }
};
