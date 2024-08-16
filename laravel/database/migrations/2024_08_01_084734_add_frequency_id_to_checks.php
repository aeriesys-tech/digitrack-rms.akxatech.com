<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('checks', function (Blueprint $table) {
            $table->dropColumn('frequency');

            $table->unsignedBigInteger('frequency_id')->after('order');
            $table->foreign('frequency_id')->references('frequency_id')->on('frequencies');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::table('checks', function (Blueprint $table) {
            $table->dropForeign(['frequency_id']);
            $table->dropColumn('frequency_id');

            $table->string('frequency');
        });
    }
};
