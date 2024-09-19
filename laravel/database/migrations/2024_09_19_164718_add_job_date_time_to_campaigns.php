<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->timestamp('job_date_time')->after('file');
            $table->string('job_no')->nullable()->after('job_date_time');
            $table->string('script')->nullable()->after('job_no');
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('job_date_time');
            $table->dropColumn('job_no');
            $table->dropColumn('script');
        });
    }
};
