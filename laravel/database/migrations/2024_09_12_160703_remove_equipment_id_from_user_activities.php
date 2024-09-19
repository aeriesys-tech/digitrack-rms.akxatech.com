<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_activities', function (Blueprint $table) {
            $table->dropForeign(['equipment_id']);
            $table->dropColumn('equipment_id');
        });
    }

    public function down(): void
    {
        Schema::table('user_activities', function (Blueprint $table) {
            $table->unsignedBigInteger('equipment_id')->nullable();
            $table->foreign('equipment_id')->references('equipment_id')->on('equipment');
        });
    }
};
