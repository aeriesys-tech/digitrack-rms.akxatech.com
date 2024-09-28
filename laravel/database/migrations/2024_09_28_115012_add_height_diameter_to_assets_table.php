<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->decimal('height')->nullable()->after('geometry_type');
            $table->decimal('diameter')->nullable()->after('height');
        });
    }


    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('height');
            $table->dropColumn('diameter');
        });
    }
};
