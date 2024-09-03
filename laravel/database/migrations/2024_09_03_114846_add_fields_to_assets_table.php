<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->foreignId('area_id')->after('asset_id');
            $table->foreign('area_id')->references('area_id')->on('areas');
            $table->integer('no_of_zones')->after('asset_name');
            $table->foreignId('functional_id')->nullable()->after('department_id');
            $table->foreign('functional_id')->references('functional_id')->on('functionals');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
            $table->dropColumn('area_id');
            $table->dropColumn('no_of_zones');
            $table->dropForeign(['functional_id']);
            $table->dropColumn('functional_id');
        });
    }
};
