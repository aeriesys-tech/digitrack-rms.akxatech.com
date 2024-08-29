<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->foreignId('list_parameter_id')->nullable()->after('service_name');
            $table->foreign('list_parameter_id')->references('list_parameter_id')->on('list_parameters');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['list_parameter_id']);
            $table->dropColumn('list_parameter_id');
        });
    }
};
