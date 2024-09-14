<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('break_down_lists', function (Blueprint $table) {
            $table->dropColumn('break_down_list_code');
            $table->dropColumn('break_down_list_name');
            $table->unsignedBigInteger('asset_id')->nullable()->after('break_down_type_id');
            $table->foreign('asset_id')->references('asset_id')->on('assets');
            $table->string('job_no', 255)->after('asset_id'); 
            $table->date('job_date')->nullable()->after('job_no'); 
            $table->text('note')->nullable()->after('job_date'); 
        });
    }

    public function down(): void
    {
        Schema::table('break_down_lists', function (Blueprint $table) {
            $table->string('break_down_list_code')->nullable(); 
            $table->string('break_down_list_name')->nullable(); 
            $table->dropForeign(['asset_id']);
            $table->dropColumn('asset_id');
            $table->dropColumn('job_no');
            $table->dropColumn('job_date');
            $table->dropColumn('note');
        });
    }
};
