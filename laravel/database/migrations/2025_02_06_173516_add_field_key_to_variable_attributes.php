<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('variable_attributes', function (Blueprint $table) {
            $table->string('field_key')->nullable()->after('list_parameter_id');
        });
    }

    public function down(): void
    {
        Schema::table('variable_attributes', function (Blueprint $table) {
            $table->dropColumn('field_key');
        });
    }
};
