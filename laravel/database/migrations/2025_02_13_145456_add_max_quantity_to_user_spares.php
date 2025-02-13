<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_spares', function (Blueprint $table) {
            $table->decimal('quantity', 15, 2)->nullable()->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('user_spares', function (Blueprint $table) {
            $table->integer('quantity')->nullable()->default(0)->change();
        });
    }
};
