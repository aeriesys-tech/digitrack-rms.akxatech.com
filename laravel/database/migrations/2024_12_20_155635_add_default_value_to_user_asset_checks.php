<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_asset_checks', function (Blueprint $table) {
            $table->string('default_value',100)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('user_asset_checks', function (Blueprint $table) {
            $table->string('default_value', 100)->nullable(false)->change();
        });
    }
};
