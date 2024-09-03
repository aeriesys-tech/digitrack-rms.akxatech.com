<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->foreignId('area_id')->nullable()->after('section_id');
            $table->foreign('area_id')->references('area_id')->on('areas');
            $table->integer('no_of_zones')->after('area_id');
            $table->foreignId('functional_id')->nullable()->after('no_of_zones');
            $table->foreign('functional_id')->references('functional_id')->on('functionals');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
            $table->dropColumn('area_id');
            $table->dropColumn('no_of_zone');
            $table->dropForeign(['functional_id']);
            $table->dropColumn('functional_id');
        });
    }
};
