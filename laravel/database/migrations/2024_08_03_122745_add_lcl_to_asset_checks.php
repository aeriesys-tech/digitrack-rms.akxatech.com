<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_checks', function (Blueprint $table) {
            $table->double('lcl',100)->nullable()->after('plant_id');
            $table->double('ucl',100)->nullable()->after('lcl');
            $table->string('default_value',100)->nullable()->after('ucl');
        });
    }

    public function down(): void
    {
        Schema::table('asset_checks', function (Blueprint $table) {
            $table->dropColumn('lcl');
            $table->dropColumn('ucl');
            $table->dropColumn('default_value');
        });
    }
};
