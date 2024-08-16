<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('assets', function (Blueprint $table) {
            $table->string('latitude',255)->nullable()->after('asset_type_id');
            $table->string('longitude',255)->nullable()->after('latitude');

            $table->unsignedBigInteger('department_id')->nullable()->after('longitude');
            $table->foreign('department_id')->references('department_id')->on('departments');

            $table->unsignedBigInteger('section_id')->nullable()->after('department_id');
            $table->foreign('section_id')->references('section_id')->on('sections');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');

            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');

            $table->dropForeign(['section_id']);
            $table->dropColumn('section_id');
        });
    }
};
