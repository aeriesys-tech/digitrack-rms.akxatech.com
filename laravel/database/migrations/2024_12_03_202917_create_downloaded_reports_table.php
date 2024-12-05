<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('downloaded_reports', function (Blueprint $table) {
            $table->id('download_report_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->datetime('date_time');
            $table->string('file_name');
            $table->string('report_name');
            $table->timestamps();
            $table->foreign('user_id')->references('user_id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('downloaded_reports');
    }
};
