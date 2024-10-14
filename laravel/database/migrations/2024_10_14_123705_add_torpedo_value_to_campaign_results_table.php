<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaign_results', function (Blueprint $table) {
            $table->string('torpedo_values')->nullable()->after('file');
            $table->string('location')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('campaign_results', function (Blueprint $table) {
            $table->dropColumn('torpedo_values');
            $table->string('location')->nullable(false)->change();  
        });
    }
};
