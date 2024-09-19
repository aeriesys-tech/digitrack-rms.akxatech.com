<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('checks', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('order');
            $table->foreign('department_id')->references('department_id')->on('departments');
        });
    }

    public function down(): void
    {
        Schema::table('checks', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};
