<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppms_datas', function (Blueprint $table) {
            $table->id('ppms_data_id');
            $table->foreignId('log_id');
            $table->date('insert_date')->nullable();
            $table->text('heat_no');
            $table->text('grade')->nullable();
            $table->integer('re_treat');
            $table->text('holding_time')->nullable();
            $table->text('processing_time')->nullable();
            $table->text('ladle_number')->nullable();
            $table->decimal('o2_ppm', 8, 3)->nullable();
            $table->integer('oxygen_after_celoxa')->nullable();
            $table->decimal('heat_size', 6, 3)->nullable();
            $table->decimal('al2_addition_bar', 6, 3)->nullable();
            $table->text('al2_addition_coil')->nullable();
            $table->integer('tapping_temperature')->nullable();
            $table->integer('lf_in_sulphur')->nullable();
            $table->integer('lf_in_temperature')->nullable();
            $table->integer('lime_consumption')->nullable();
            $table->integer('tundish_temperature')->nullable();
            $table->integer('super_heat_avg')->nullable();
            $table->integer('super_heat_max')->nullable();
            $table->integer('lifting_temperature')->nullable();
            $table->integer('lf_slag_report')->nullable();
            $table->timestamps();
            $table->foreign('log_id')->references('log_id')->on('logs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppms_datas');
    }
};
